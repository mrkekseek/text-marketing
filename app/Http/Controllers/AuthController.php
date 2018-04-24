<?php

namespace App\Http\Controllers;

use App\User;
use App\Homeadvisor;
use App\DefaultText;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Jobs\SignUp;
use App\Jobs\Support;
use App\Jobs\Recovery;
use App\Jobs\SendHomeadvisorActivationDelay;
use App\Http\Services\UsersService;
use App\Http\Services\LinksService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthController extends Controller
{
    public $salt = 'eEZue4JfUvJJKn9N';

    public function info()
    {
        return auth()->user();
    }
    
    public function signin(SignInRequest $request)
    {
        $old_pass = hash_hmac('sha1', $request->password, $this->salt);
        $users = User::where('password', $old_pass)->get();
        foreach ($users as $user) {
            $user->update([
                'password' => UsersService::password($request->password)
            ]);
        }
        $user = User::where('email', $request->email)->first();

        if (auth()->validate($request->all()) && $user->plans_id != 'canceled-contractortexter') {
            auth()->attempt($request->all());
            return $this->message('You are in', 'success');
        }

        return $this->message('Invalid Email or Password');
    }

    public function signup(SignUpRequest $request)
    {
        $data = $request->only(['plans_id', 'email', 'password', 'firstname', 'lastname']);
        $data['plans_id'] = $data['plans_id'].'-'.strtolower(config('app.name'));
        $data['type'] = 2;
		$data['teams_leader'] = true;
		$data['active'] = true;
		$data['password'] = UsersService::password($data['password']);
		$data['teams_id'] = UsersService::createTeam($data);
        $data['trial_ends_at'] = UsersService::trialEndsAt($data['plans_id']);
        if ( ! empty($request->view_phone)) {
            $data['view_phone'] = $request->view_phone;
            $data['phone'] = UsersService::phoneToNumber($data);
        }
        $data = array_filter($data, 'strlen');

        $user = User::create($data);
        LinksService::create($user);
        $text = DefaultText::first();

        if ( ! empty($request->rep)) {
            $user->homeadvisors()->create([
                'rep' => $request->rep,
                'emails' => '',
                'text' => $text->instant,
                'first_followup_active' => Homeadvisor::FIRST_FOLLOWUP_ACTIVE,
                'first_followup_delay' => $text->first_followup_delay,
                'first_followup_text' => $text->first_followup,
                'second_followup_active' => Homeadvisor::SECOND_FOLLOWUP_ACTIVE,
                'second_followup_delay' => $text->second_followup_delay,
                'second_followup_text' => $text->second_followup,
            ]);
        }

        auth()->login($user);
        $owner = User::where('owner', 1)->first();

        $job = (new SignUp($owner, $user))->delay(0)->onQueue('texts');
        $this->dispatch($job);

        $first_delay_date = Carbon::now()->addDays(Homeadvisor::FIRST_DELAY_AFTER_SIGNUP);
        $first_delay = Carbon::now()->diffInSeconds($first_delay_date);
        $second_delay_date = Carbon::now()->addDays(Homeadvisor::SECOND_DELAY_AFTER_SIGNUP);
        $second_delay = Carbon::now()->diffInSeconds($second_delay_date);

        SendHomeadvisorActivationDelay::dispatch($user)->delay($first_delay)->onQueue('texts');
        SendHomeadvisorActivationDelay::dispatch($user, Homeadvisor::SECOND_DELAY_AFTER_SIGNUP)->delay($second_delay)->onQueue('texts');

        return $this->message('You were successfully registered', 'success');
    }

    public function createSubscriptions($user)
    {
        /*$user->newSubscription('main', 'home-advisor-contractortexter')->create([
            'email' => $user->email,
            'trial_ends_at' => Carbon::now()->addDays(14),
        ]);
        $user = User::create([
            'trial_ends_at' => ,
        ]);*/
    }

    public function signout()
    {
        $user = auth()->user();
        if ( ! empty($user->admins_id)) {
            $admin = User::find($user->admins_id);
            auth()->login($admin);
            
            $user->admins_id = 0;
            $user->save();
        } else {
            auth()->logout();
        }

        return $this->message('You are out', 'success');
    }

    public function support(Request $request)
    {
        $data = $request->only(['name', 'email', 'message', 'subject']);
        $owner = User::where('owner', 1)->first();

        $job = (new Support($owner, $data))->onQueue('emails');
        $this->dispatch($job);

        return $this->message('Your email successfully sent', 'success');
    }

    public function recovery(Request $request)
    {
        $user = User::where('email', strtolower($request->email))->first();
        if ( ! empty($user)) {
            $password = crypt($user->password, time());
            $user->password = UsersService::password($password);
            $user->save();

            $job = (new Recovery($request->email, $password))->onQueue('emails');
            $this->dispatch($job);

            return $this->message('New password was sent to your email address', 'success');
        }
        return $this->message('Invalid email');
    }
}
