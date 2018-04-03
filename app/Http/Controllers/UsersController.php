<?php

namespace App\Http\Controllers;

use App\User;
use App\Team;
use App\Homeadvisor;
use App\Survey;
use App\Client;
use App\Dialog;
use App\Text;
use App\Message;
use App\Seance;
use App\Review;
use App\Answer;
use App\Link;
use App\Url;
use App\ContactList;
use App\SocialReview;
use App\DefaultText;

use App\Libraries\Api;
use App\Libraries\Jwt;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\UsersService;
use App\Http\Services\LinksService;
use App\Http\Requests\UsersCreateRequest;
use App\Http\Requests\UsersPasswordRequest;
use App\Http\Requests\PartnersCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
	public $publickey = '-----BEGIN PUBLIC KEY-----
						MIIBIDANBgkqhkiG9w0BAQEFAAOCAQ0AMIIBCAKCAQEA6ymkvlLl4cl/ya6a9WW3
						gwDtwdViEFuFh6I/JZ7v9OsWv+v9iOtK07zCqZ73Ma0uf3dq6Mun0tFruFqbxqGd
						EJZxxNfs0UEpFSfQIJEUNEjUfmsF+a9ewAzzsO334+4mptfql4234X1Q3d0vSPJw
						Iw8oNc3/6Mk7Kkic5bQ/poHNSeu7G0Rf0RSyWSC1JNg5+JR9siEo8vm/dSQZJSdg
						pOQh97re9idpVotDANjHKGEwpeihOV70xLG46lO+XgQUio/z8MGfNtNq8TQ3MXwz
						d9x2hLp7Ww5FDp4VaiW94nKj6Gq+kTHo8mhI3pg7iE5myIE13/rPp3+CC8owwtt5
						wQIBJQ==
						-----END PUBLIC KEY-----';

	public function migrateDialogs(Request $request)
	{
		$data = $request->json()->all();
		foreach ($data as $item) {
			$user_id = $this->getUserId($item['user_email']);
			$client_id = $this->getClientId($item['phone']);
			
			if (! empty($user_id) && ! empty($client_id) && ! empty($item['text'])) {
				$dialog = new Dialog();
				$dialog->users_id = $user_id;
				$dialog->clients_id = $client_id;
				$dialog->text = $item['text'];
				$dialog->my = $item['my'];
				$dialog->new = $item['new'];
				$dialog->status = $item['status'];
				$dialog->created_at = $item['created_at'];
				$dialog->updated_at = $item['updated_at'];
				$dialog->save();
			}
		}
	}

	public function migrateClicked(Request $request)
	{
		$data = $request->json()->all();
		foreach ($data as $item) {
			$client = Client::where('phone', trim($item['phones_number']))->where('source', 'HomeAdvisor')->first();
			if ( ! empty($client)) {
				$client->update(['clicked' => 1]);
			}
		}
	}

	public function migrateToken(Request $request)
	{
		$data = $request->json()->all();
		foreach ($data as $item) {
			User::where('email', $item['email'])->update(['facebook_token' => $item['facebook_token']]);
		}
	}

	/*public function migratePhones(Request $request)
	{
		$data = $request->json()->all();

		foreach ($data as $phone) {
			$team_id = $this->getTeamId($phone['user_email']);
			if ( ! empty($team_id)) {
				$client = new Client();
				$client->team_id = $team_id;
				$client->firstname = $phone['phones_firstname'];
				$client->lastname = ! empty($phone['phones_lastname']) ? $phone['phones_lastname'] : '';
				$client->phone = $phone['phones_number'];
				$client->view_phone = $phone['phones_number'];
				$client->email = '';
				$client->source = $phone['phones_source'];
				$client->created_at = $phone['phones_add'];
				$client->updated_at = $phone['phones_add'];
				$client->save();
			}
			
		}
	}*/

	public function migratePhones(Request $request)
	{
		$data = $request->json()->all();
		foreach ($data as $phone) {
			$team_id = $this->getTeamId($phone['user_email']);
			if ( ! empty($team_id)) {
				$client = new Client();
				$client->team_id = $team_id;
				$client->firstname = $phone['phones_firstname'];
				$client->lastname = ! empty($phone['phones_lastname']) ? $phone['phones_lastname'] : '';
				$client->phone = $phone['phones_number'];
				$client->view_phone = $phone['phones_number'];
				$client->email = '';
				$client->source = $phone['phones_source'];
				$client->created_at = $phone['phones_add'];
				$client->updated_at = $phone['phones_add'];
				$client->save();
			}
		}
	}

	public function migrateSocials(Request $request)
	{
		$data = $request->json()->all();
		foreach ($data as $key => $value) {
			$id = $this->getUserId($key);
			if ( ! empty($id)) {
				foreach ($value as $item => $row) {
					if (strlen($row) <= 191) {
						$url = Url::where('user_id', $id)->where('name', $item)->where('url', '')->update(['url' => $row]);
					}
				}
			}
		}
	}

	public function getClientId($phone = '')
	{
		if ( ! empty($phone)) {
			$client = Client::where('phone', $phone)->first();
			if ( ! empty($client)) {
				return $client->id;
			}
		}
		return false;
	}

	public function getUserId($user_email = '')
	{
		if ( ! empty($user_email)) {
			$user = User::where('email', $user_email)->first();
			return $user->id;
		}
		return false;
	}

	public function getTeamId($user_email = '')
	{
		if ( ! empty($user_email)) {
			$user = User::where('email', $user_email)->first();
			return $user->teams_id;
		}
		return false;
	}

	public function migrateStoreTexter(Request $request)
	{
		$data = $request->json()->all();
		foreach ($data as $item) {
			$team = new Team();
			$team->name = $item['name'];
			$team->save();

			if ( ! empty($item['users'])) {
				foreach ($item['users'] as $row) {
					$user = new User();
					$user->company_name = $row['company_name'];
					$user->company_status = $row['company_status'];
					$user->plans_id = 'text-contractortexter';
					$user->teams_id = $team->id;
					$user->teams_leader = 1;
					$user->type = $row['type'];
					$user->email = $row['email'];
					$user->password = $row['password'];
					$user->firstname = $row['firstname'];
					$user->lastname = $row['lastname'];
					$user->phone = $row['phone'];
					$user->view_phone = $row['phone'];
					$user->additional_phones = $row['additional_phones'];
					$user->active = $row['active'];
					$user->offset = 0;
					$user->save();

					$link = new Link();
					$link->users_id = $user->id;
					$link->code = $row['links']['code'];
					$link->url = $row['links']['url'];
					$link->success = $row['links']['success'];
					$link->save();

					$homeadvisor = new Homeadvisor();
					$homeadvisor->users_id = $user->id;
					$homeadvisor->text = $row['homeadvisor']['text'];
					$homeadvisor->additional_phones = $row['homeadvisor']['additional_phones'];
					$homeadvisor->rep = $row['homeadvisor']['rep'];
					$homeadvisor->send_request = $row['homeadvisor']['send_request'];
					$homeadvisor->active = $row['homeadvisor']['active'];
					$homeadvisor->save();

					if ( ! empty($row['clients'])) {
						foreach ($row['clients'] as $client_row) {
							$client = new Client();
							$client->team_id = $team->id;
							$client->firstname = ! empty($client_row['firstname']) ? $client_row['firstname'] : '';
							$client->lastname = ! empty($client_row['lastname']) ? $client_row['lastname'] : '';
							$client->phone = ! empty($client_row['phone']) ? $client_row['phone'] : '';
							$client->view_phone = ! empty($client_row['view_phone']) ? $client_row['view_phone'] : '';
							$client->email = ! empty($client_row['email']) ? $client_row['email'] : '';
							$client->source = ! empty($client_row['source']) ? $client_row['source'] : '';
							$client->created_at = $client_row['created_at'];
							$client->updated_at = $client_row['updated_at'];
							$client->save();

							if ( ! empty($client_row['dialogs'])) {
								foreach ($client_row['dialogs'] as $dialog_row) {
									$dialog = new Dialog();
									$dialog->users_id = $user->id;
									$dialog->clients_id = $client->id;
									$dialog->text = $dialog_row['text'];
									$dialog->my = $dialog_row['my'];
									$dialog->new = $dialog_row['new'];
									$dialog->status = $dialog_row['status'];
									$dialog->created_at = $dialog_row['created_at'];
									$dialog->updated_at = $dialog_row['updated_at'];
									$dialog->save();
								}
							}
						}
					}
				}
			}
		}
	}

	public function migrate(Request $request)
	{
		$data = $request->json()->all();
		Team::truncate();
		User::truncate();
		Homeadvisor::truncate();
		Review::truncate();
		Dialog::truncate();
		Survey::truncate();
		Seance::truncate();
		Text::truncate();
		Answer::truncate();
		Message::truncate();
		Client::truncate();
		Link::truncate();
		Url::truncate();
		ContactList::truncate();

		$clients_global = [];

		foreach ($data as $item) {
			$team = new Team();
			$team->name = $item['name'];
			$team->save();

			if ( ! empty($item['users'])) { 
				foreach ($item['users'] as $row) {
					$user = new User();
					$user->company_name = $row['company_name'];
					$user->company_status = $row['company_status'];
					$user->plans_id = $row['plans_id'];
					$user->teams_id = $team->id;
					$user->teams_leader = $row['teams_leader'];
					$user->type = $row['type'];
					$user->email = $row['email'];
					$user->password = $row['password'];
					$user->firstname = $row['firstname'];
					$user->lastname = $row['lastname'];
					$user->phone = $row['phone'];
					$user->view_phone = $row['phone'];
					$user->additional_phones = $row['additional_phones'];
					$user->active = $row['active'];
					$user->offset = 0;
					$user->save();

					$link = new Link();
					$link->users_id = $user->id;
					$link->code = $row['links']['code'];
					$link->url = $row['links']['url'];
					$link->success = $row['links']['success'];
					$link->save();

					$homeadvisor = new Homeadvisor();
					$homeadvisor->users_id = $user->id;
					$homeadvisor->text = $row['homeadvisor']['text'];
					$homeadvisor->additional_phones = $row['homeadvisor']['additional_phones'];
					$homeadvisor->rep = $row['homeadvisor']['rep'];
					$homeadvisor->send_request = $row['homeadvisor']['send_request'];
					$homeadvisor->active = $row['homeadvisor']['active'];
					$homeadvisor->save();

					$survey = new Survey();
					$survey->user_id = $user->id;
					$survey->text = ! empty($row['surveys']['text']) ? $row['surveys']['text'] : '';
					$survey->email = ! empty($row['surveys']['email']) ? $row['surveys']['email'] : '';
					$survey->subject = ! empty($row['surveys']['subject']) ? $row['surveys']['subject'] : '';
					$survey->sender = ! empty($row['surveys']['sender']) ? $row['surveys']['sender'] : '';
					$survey->alerts_emails = $row['surveys']['alerts_emails'];
					$survey->alerts_stars = $row['surveys']['alerts_stars'];
					$survey->alerts_often = $row['surveys']['alerts_often'];
					$survey->save();

					foreach ($user['urls'] as $url) {
						$url = new Url();
						$url->user_id = $user->id;
						$url->name = ! empty($url['name']) ? $url['name'] : '';
						$url->social_id = ! empty($url['social_id']) ? $url['social_id'] : '';
						$url->active = ! empty($url['active']) ? $url['active'] : false;
						$url->default = ! empty($url['default']) ? $url['default'] : false;
						$url->save();
					}

					$list_ids = [];
					$clients = [];
					$lists_id = [];
					$new_list_id = [];

					foreach ($row['lists'] as $old => $list_row) {
						if ( ! in_array($old, $lists_id)) {
							$list = new ContactList();
							$list->name = ! empty($list_row['name']) ? $list_row['name'] : '';
							$list->users_id = $user->id;
							$list->save();

							$new_list_id[$old] = $list->id;

							foreach ($item['clients'] as $client_row) {
								if ( ! empty($client_row['lists_id']) && $client_row['lists_id'] == $old && ! in_array($client_row['phones_id'], $clients)) {
									$client = new Client();
									$client->team_id = $team->id;
									$client->firstname = ! empty($client_row['firstname']) ? $client_row['firstname'] : '';
									$client->lastname = ! empty($client_row['lastname']) ? $client_row['lastname'] : '';
									$client->phone = ! empty($client_row['phone']) ? $client_row['phone'] : '';
									$client->view_phone = ! empty($client_row['view_phone']) ? $client_row['view_phone'] : '';
									$client->email = ! empty($client_row['email']) ? $client_row['email'] : '';
									$client->source =  ! empty($client_row['source']) ? $client_row['source'] : '';
									$client->save();

									$clients[] = $client_row['phones_id'];
									$clients_global[$client_row['phones_id']] = $client->id;
								}
							}
							$lists_id[] = $old;
						}
					}

					foreach ($user['messages'] as $message) {
						$temp = [];
						foreach ($message['lists_id'] as $ids_lists) {
							$temp[] = $new_list_id[$ids_lists];
						}

						$message = new Message();
						$message->user_id = $user->id;
						$mrssage->lists_id = implode(',', $temp);
						$message->text = $message['text'];
						$message->file = $message['file'];
						$message->schedule = $message['schedule'];
						$message->switch = $message['switch'];
						$message->x_day = $message['x_day'];
						$message->date = $message['date'];
						$message->finish_date = $message['finish_date'];
						$message->token = $message['token'];
						$message->active = $message['active'];
						$message->save();

						$date = Carbon::now();
						$date->timestamp = $message->date;

						$text = new Text();
						$text->message_id = $message->id;
						$text->phones = 0;
						$text->finish = 1;
						$text->message = '';
						$text->send_at = $date;
						$text->save();
					}

					foreach ($user['dialogs'] as $dialogs_row) {
						$dialog = new Dialog();
						$dialog->users_id = $user->id;
						$dialog->clients_id = $clients_global[$dialogs_row['clients_id']];
						$dialog->text = $dialogs_row['text'];
						$dialog->my = $dialogs_row['my'];
						$dialog->new = $dialogs_row['new'];
						$dialog->status = $dialogs_row['status'];

						$dialog->save();
					}
				}

				if ( ! empty($item['clients'])) {
					foreach ($item['clients'] as $client_row) {
						$client = new Client();
						$client->team_id = $team->id;
						$client->firstname = ! empty($client_row['firstname']) ? $client_row['firstname'] : '';
						$client->lastname = ! empty($client_row['lastname']) ? $client_row['lastname'] : '';
						$client->phone = ! empty($client_row['phone']) ? $client_row['phone'] : '';
						$client->view_phone = ! empty($client_row['view_phone']) ? $client_row['view_phone'] : '';
						$client->email = ! empty($client_row['email']) ? $client_row['email'] : '';
						$client->source = ! empty($client_row['source']) ? $client_row['source'] : '';
						$client->save();

						if ( ! empty($client_row['seances'])) {
							foreach ($client_row['seances'] as $seances_row) {
								$review = new Review();
								$review->user_id = $user->id;
								$review->survey_id = $survey->id;
								$review->save();

								$completed = Carbon::now();
								$completed->timestamp = $seances_row['completed'];

								$seance = new Seance();
								$seance->review_id = $review->id;
								$seance->client_id = $client->id;
								$seance->url_id = 0;
								$seance->code = $seances_row['code'];
								$seance->url = $seances_row['url'];
								$seance->date = $seances_row['date'];
								$seance->completed = Carbon::now();
								$seance->show = $seances_row['show'];
								$seance->type = $seances_row['type'];
								$seance->finish = $seances_row['finish'];
								$seance->success = $seances_row['success'];
								$seance->message = $seances_row['message'];
								$seance->alert = $seances_row['alert'];

								$seance->save();

								if ( ! empty($seances_row['answers'])) {
									foreach ($seances_row['answers'] as $answer_row) {
										$answer = new Answer();
										$answer->seance_id = $seance->id;
										$answer->question_id = $answer_row['question_id'];
										$answer->value = $answer_row['value'];
										$answer->save();
									}
								}
							}
						}
					}
				}
			}
		}
	}

    public function info($id)
    {
    	return User::find($id);
    }

    public function all()
	{
		return User::allUsers()->each(function($item, $key) {
			$ha = $item->homeadvisors()->first();
			$item->rep = $ha['rep'];
			return $item;
		});
	}

	public function partners()
	{
		return auth()->user()->partners()->with('urls')->get();
	}

	public function employees()
	{
		return auth()->user()->employees()->get();
	}

	public function create(UsersCreateRequest $request)
	{
		$data = $request->only(['plans_id', 'firstname', 'lastname', 'email', 'password', 'view_phone']);
		$data['type'] = 2;
		$data['teams_leader'] = true;
		$data['active'] = true;
		$data['password'] = UsersService::password($data['password']);
		$data['phone'] = UsersService::phoneToNumber($data);
		$data['teams_id'] = UsersService::createTeam($data);
		$data['offset'] = config('app.offset');
		$data = array_filter($data, 'strlen');

		$user = User::create($data);
		LinksService::create($user);

		return $this->message('Teammate was successfully saved', 'success');
	}

	public function update(UsersCreateRequest $request, $id)
	{
		$data = $request->only(['plans_id', 'firstname', 'lastname', 'email', 'password', 'view_phone']);
		$data['password'] = UsersService::password($data['password']);
		$data['phone'] = UsersService::phoneToNumber($data);
		$data = array_filter($data, 'strlen');

		$user = User::find($id)->update($data);

		return $this->message('Teammate was successfully saved', 'success');
	}

	public function partnersCreate(PartnersCreateRequest $request)
	{
		$data = $request->only(['firstname', 'lastname', 'email', 'view_phone']);
		$data['type'] = 2;
		$data['teams_leader'] = false;
		$data['active'] = true;
		$data['password'] = UsersService::password(config('app.name'));
		$data['phone'] = UsersService::phoneToNumber($data);
		$data['teams_id'] = auth()->user()->teams_id;
		$data['plans_id'] = auth()->user()->plans_id;
		$data['offset'] = config('app.offset');
		$data = array_filter($data, 'strlen');

		$user = User::create($data);
		LinksService::create($user);

		$this->message('Partner was successfully saved', 'success');
		return $user;
	}

	public function employeesCreate(PartnersCreateRequest $request)
	{
		$data = $request->only(['firstname', 'lastname', 'email', 'view_phone', 'avatar']);

		$data['type'] = 2;
		$data['teams_leader'] = false;
		$data['active'] = true;
		$data['password'] = UsersService::password(config('app.name'));
		$data['phone'] = UsersService::phoneToNumber($data);
		$data['teams_id'] = auth()->user()->teams_id;
		$data['plans_id'] = auth()->user()->plans_id;
		$data['offset'] = config('app.offset');
		$data['employee'] = true;
		$data = array_filter($data, 'strlen');

		$user = User::create($data);
		LinksService::create($user);

		if ( ! empty($data['avatar'])) {
			$temp = explode('.', $data['avatar']);
			$name = $user->id.'.'.$temp[1];
			Storage::move(str_replace('storage', 'public', $data['avatar']), 'public/upload/employees/'.$user->id.'/'.$name);
			$file = 'storage/upload/employees/'.$user->id.'/'.$name;
			$user->update(['avatar' => $file]);
		}

		$this->message('Employee was successfully saved', 'success');
		return $user;
	}

	public function employeesUpdate(PartnersCreateRequest $request, User $user)
	{
		$data = $request->only(['firstname', 'lastname', 'email', 'view_phone', 'avatar']);
		$data['phone'] = UsersService::phoneToNumber($data);
		$file = '';
		if ( ! empty($data['avatar'])) {
			$temp = explode('.', $data['avatar']);
			$name = $user->id.'.'.$temp[1];
			if (strpos($data['avatar'], 'temp') !== false) {
				Storage::deleteDirectory('public/upload/homeadvisor/'.$user->id);
				Storage::copy(str_replace('storage', 'public', $data['avatar']), 'public/upload/employees/'.$user->id.'/'.$name);
			}
			$file = 'storage/upload/employees/'.$user->id.'/'.$name;
		} else {
			Storage::deleteDirectory('public/upload/employees/'.$user->id);
		}
		$data['avatar'] = $file;
		
		$user->update($data);

		$this->message('Teammate was successfully saved', 'success');
		return $user;
	}

	public function employeesRemove(User $user)
	{
		$user->links()->delete();

		$user->delete();
		return $this->message('Partner was successfully removed', 'success');
	}

	public function partnersUpdate(PartnersCreateRequest $request, User $user)
	{
		$data = $request->only(['firstname', 'lastname', 'email', 'view_phone']);
		$data['phone'] = UsersService::phoneToNumber($data);
		$data = array_filter($data, 'strlen');

		$user->update($data);

		$this->message('Teammate was successfully saved', 'success');
		return $user;
	}

	public function partnersRemove(User $user)
	{
		$user->links()->delete();

		$user->delete();
		return $this->message('Partner was successfully removed', 'success');
	}

	public function profile(UsersCreateRequest $request)
	{
		$data = $request->only(['firstname', 'lastname', 'email', 'view_phone']);
		$data['phone'] = UsersService::phoneToNumber($data);
		$data['offset'] = config('app.offset');
		$data = array_filter($data, 'strlen');

		$user = auth()->user()->update($data);

		return $this->message('Profile was successfully saved', 'success');
	}

	public function remove($id)
	{
		$user = User::find($id);
		$user->links()->delete();

		$user->delete();
		return $this->message('User was successfully removed', 'success');
	}

	public function magic($id)
	{
		$user = User::find($id);
		$user->admins_id = auth()->id();
		$user->save();

		auth()->login($user);
	}

	public function magicInbox(User $user, Client $client, Dialog $dialog)
	{
		auth()->login($user);
		$dialog->update(['reply_viewed' => 1]);
		$link = config('app.url').'/marketing/inbox/'.$client->id;
		return redirect($link);
	}

	public function magicReferral($hash)
	{
		$user = User::whereRaw('MD5(CONCAT(id, created_at)) = "'. $hash.'"')->first();
		if ($user) {
			auth()->login($user);
			$user_hash = md5($user.$user->created_at);
			$link = config('app.url').'/ha/referral/'.$user_hash;
			return redirect($link);
		}
	}

	public function password(UsersPasswordRequest $request)
	{
		$user = auth()->user();
		if (Hash::check($request->old_password, $user->password)) {
			$user->password = UsersService::password($request->password);
			$user->save();
			return $this->message('Password was successfully changed', 'success');
		}

		return $this->message('Old Password is incorrect');
	}

	public function company(Request $request, User $user = null)
	{
		$user = empty($user) ? auth()->user() : $user;
		$status = 'pending';
		$data = Api::company($request->company);
		if ($data['code'] == 200) {
			$status = $data['data'];
		}

		$user->update([
			'company_name' => $request->company,
			'company_status' => $status,
		]);

		return ['status' => $status];
	}

	public function status(User $user = null)
	{
		$user = empty($user) ? auth()->user() : $user;
		return ['status' => $user->company_status];
	}

	public function push(Request $request)
    {
    	$data = $request->json()->all();
    	$users = User::where('company_name', $data['name'])->get();

    	foreach ($users as $user) {
    		$user->update(['company_status' => $data['status']]);
    	}
    }

    public function saveSettings(Request $request)
    {
    	$data = $request->all();
    	auth()->user()->update([
			'company_name' => $data['company_name'],
			'additional_phones' => implode(',', $data['additional_phones']),
		]);

		return $this->message('Settings was successfully saved.', 'success');
    }

    public function facebookToken()
    {
    	$items = [];
    	$users = User::facebookTokens();
    	foreach ($users as $user) {
    		$items[] = [
				'users_token' => $user['facebook_token'],
				'page_id' => $user['facebook_url']['social_id']
			];
    	}
    	$data = json_encode(['data' => $items]);
    	$encode = Jwt::encode($data, $this->publickey);
    	
    	//$decode = JWT::decode($encode, $this->publickey, array('HS256'));
    	
    	return $encode;
    }

    public function facebookReviews(Request $request)
    {
    	$data = $request->json()->all();
    	if (empty($data)) {
    		$data = $request->all();
    	}

    	$this->saveLog($data, 'FACEBOOK REVIEWS');

    	if ( ! empty($data)) {
    		foreach ($data as $review)
			{
				$review = SocialReview::where('hash', md5($review['review_text']))->where('type', 'Facebook')->first();
				if (empty($review)) {
					$url = Url::where('social_id', $review['page_id'])->where('default', 1)->where('name', 'Facebook')->first();

					$review = new SocialReview();
					$review->user_id = $url->user_id;
					$review->hash = md5($review['review_text']);
					$review->text = $review['review_text'];
					$review->author = $review['reviewerName'];
					$review->rating = $review['rating'];
					$review->date = $review['created_time'];
					$review->type = 'Facebook';
					$review->save();
				}
			}
    	}
    }

    public function googlePlaceId()
    {
    	$items = [];
    	$users = User::googlePlaceIds();
    	foreach ($users as $user) {
    		$items[] = [
				'google_place' => $user['google_url']['url'],
				'users_id' => $user['id']
			];
    	}
    	$data = json_encode(['data' => $items]);
		return $data;
    }

    public function googleReviews(Request $request)
    {
    	$data = $request->json()->all();
    	if (empty($data)) {
    		$data = $request->all();
    	}

    	$this->saveLog($data, 'GOOGLE REVIEWS');

    	if ( ! empty($data)) {
    		foreach ($data as $review) {
    			$review = SocialReview::where('hash', md5($review['text']))->where('type', 'Google')->first();
    			if (empty($review)) {
    				$user = User::find($review['users_id']);

    				$review = new SocialReview();
					$review->user_id = $user->id;
					$review->hash = md5($review['text']);
					$review->text = $review['text'];
					$review->author = $review['author_name'];
					$review->rating = 0;
					$review->date = $review['time'];
					$review->type = 'Google';
					$review->save();
    			}
    		}
    	}
    }

    public function getDefaultTexts()
    {
    	return DefaultText::first();
    }

    public function updateDefaultTexts(Request $request, DefaultText $text)
    {
		$data = $request->only('texts');
		
		$text->thank_you_signup = $data['texts']['thank_you_signup'];
		$text->two_days_not_active = $data['texts']['two_days_not_active'];
		$text->four_days_not_active = $data['texts']['four_days_not_active'];
		$text->new_user = $data['texts']['new_user'];
		$text->instant = $data['texts']['instant'];
		$text->first_followup = $data['texts']['first_followup'];
		$text->first_followup_delay = $data['texts']['first_followup_delay'];
		$text->second_followup = $data['texts']['second_followup'];
		$text->second_followup_delay = $data['texts']['second_followup_delay'];
		$text->lead_clicks_alert = $data['texts']['lead_clicks_alert'];
		$text->lead_reply_alert = $data['texts']['lead_reply_alert'];
		$text->lead_clicks = $data['texts']['lead_clicks'];
		$text->user_click_reminder = $data['texts']['user_click_reminder'];
		$text->update();

    	$this->message('Settings was successfully saved', 'success');
    }

    public function companyNames()
    {
    	return User::companyNames();
    }

    public function saveLog($data, $source)
    {
        if ( ! file_exists('logs')) {
            mkdir('logs', 0777);
        }
        file_put_contents('logs/logger.txt', date('[Y-m-d H:i:s] ').$source.': '.print_r($data, true).PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
