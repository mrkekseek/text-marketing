<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Jobs\SignUp;
use App\Jobs\Support;
use App\Jobs\Recovery;
use App\Http\Services\UsersService;
use App\Http\Services\LinksService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function info()
    {
        return auth()->user();
    }
    
    public function signin(SignInRequest $request)
    {
        if (auth()->validate($request->all())) {
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
        $data = array_filter($data, 'strlen');

        $user = User::create($data);
        LinksService::create($user);

        if ( ! empty($request->rep)) {
            $user->homeadvisors()->create(['rep' => $request->rep]);
        }

        auth()->login($user);
        $owner = User::where('owner', 1)->first();

        $job = (new SignUp($owner, $user))->onQueue('emails');
        $this->dispatch($job);

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
