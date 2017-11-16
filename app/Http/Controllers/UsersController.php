<?php

namespace App\Http\Controllers;

use App\User;
use App\Mail\ActivateUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function info($id = false)
    {
    	echo 'sasdasda';
    }

    public function get($post = [])
	{
		return User::where('type', '!=', 1)->get();
	}

	public function save($post = [])
	{
		$validator = $this->validate(request(), [
            'email' => 'required|email|unique:users,email'.(empty($post['id']) ? '' : ','.$post['id']),
            'teams_id' => 'required',
            'firstname' => 'required',
            'password' => 'required_without:id',
        ]);

        if ( ! $validator->fails()) {

			$user = User::firstOrNew(['id' => empty($post['id']) ? 0 : $post['id']]);
			$user->plans_code = $post['plans_code'];
			$user->teams_id = $post['teams_id'];
			$user->teams_leader = $post['teams_leader'];
			$user->type = 2;
			$user->email = strtolower($post['email']);
			$user->firstname = $post['firstname'];
			$user->lastname = $post['lastname'];
			$user->phone = $this->phoneToNumber($post['phone']);
			$user->active = $post['active'];

			if ( ! empty($post['password'])) {
				$user->password = bcrypt($post['password']);
			}

			$user->save();

			if ( ! empty($post['send']) && empty($post['active'])) {
				$this->sendActivationEmail($user);
			}

			return $this->message(__('Teammate was successfully saved'), 'success');
		}

		return false;

		/*if ( ! empty($post['teams_leader']))
		{
			$this->db->where('teams_id', $post['teams_id']);
			$this->db->update('users', array('teams_leader' => FALSE));
		}

		$plans_id = $row['plans_id'];

		if (empty($row['plans_id']) && ! empty($post['plans_id']))
		{
			$plans_id = $post['plans_id'];
		}

		if (! empty($row['plans_id']) && $post['plans_id'] != $row['plans_id'] && ! empty($post['plans_id']))
		{
			$plans_array[] = [
				'plans_id' => $post['plans_id'],
				'users_id' => $row['users_id'],
				'users_sub_id' => $row['users_sub_id']
			];

			$this->change_plan($plans_array);

			$plans_id = $post['plans_id'];
		}

		if (empty($post['plans_id']))
		{
			$this->cancel_subscription(['users_sub_id' => $row['users_sub_id'], 'users_id' => $row['users_id']]);
			$plans_id = '';
		}

		if ( ! empty($plans_id))
		{
			$temp = explode('-', $plans_id);
			$post['users_send_type'] = ($temp[0] == 'text') ? 1 : 0;
		}*/

		/*$data_array = [
			'teams_id' => $post['teams_id'],
			'teams_leader' => ! empty($post['teams_leader']) ? $post['teams_leader'] : FALSE,
			'users_hospital' => ! empty($post['users_hospital']) ? $post['users_hospital'] : FALSE,
			'users_type' => ! empty($post['users_hospital']) ? 4 : 2,
			'users_firstname' => $post['users_firstname'],
			'users_lastname' => $post['users_lastname'],
			'users_email' => $email,
			'users_phone' => ! empty($post['users_phone']) ? str_replace('-', '', $post['users_phone']) : '',
			'users_active' => $post['users_active'],
			'plans_id' => $plans_id,
			'users_trial_plans' => $plans_id,
			'users_first_time' => 1,
			'users_send_type' => ! empty($post['users_send_type']) ? $post['users_send_type'] : 0
		];

		if (empty($post['users_id']))
		{
			$data_array['users_responses'] = USERS_RESPONSES;
			$data_array['users_negatively_response'] = USERS_NEGATIVELY_RESPONSE;
			$data_array['users_notify_enable'] = USERS_NOTIFY_ENABLE;
			$data_array['users_analysis_providers'] = USERS_ANALYSIS_PROVIDERS;
			$data_array['users_email_field'] = USERS_EMAIL_FIELD;

			$data_array['users_date_field'] = FALSE;

			$data_array['users_password'] = $this->password_generate($post['users_password']);
			$data_array['users_add'] = time();
			$this->db->insert('users', $data_array);
			$post['users_id'] = $this->db->insert_id();

			if ( ! empty($plans_id))
			{
				$this->db->insert('counts_sent', ['users_id' => $post['users_id'], 'counts_date' => time()]);
			}
		}
		else
		{
			if ( ! empty($post['users_password']))
			{
				$data_array['users_password'] = $this->password_generate($post['users_password']);
			}

			$this->db->where('users_id', $post['users_id']);
			$this->db->update('users', $data_array);

			$this->db->where('users_id', $post['users_id']);
			$this->db->limit(1);
			$row = $this->db->get('users')->row_array();
			if ( ! empty($row['links_code']))
			{
				$link_data = [
					'links_firstname' => $data_array['users_firstname'],
					'links_lastname' => $data_array['users_lastname'],
					'links_phone' => $data_array['users_phone'],
					'teams_id' => $data_array['teams_id']
				];

				$this->db->where('links_code', $row['links_code']);
				$this->db->update('home_advisor_links', $link_data);
			}
		}*/

		/*if ( ! empty($post['users_id']))
		{
			if ( ! empty($post['users_send']) && ! empty($email) && ! empty($post['users_password']))
			{
				$email_data = array('email' => $email,
									'password' => $post['users_password'],
									'hash' => str_replace(array('/', '.'), '_', md5($post['users_id'].$email)));
				$this->emails->send($email, 'activation', "Activation", $email_data);
			}

			$this->pub->message($this->langs->get("Teammate was successfully saved"), 'Success');
			return $this->get();
		}
		else
		{
			return $this->pub->message($this->langs->get("You have an error. New user didn't saved"));
		}*/
	}

	public function teamsLeader($post = [])
	{
		$user = User::find($post['id']);
		$user->teams_leader = $post['teams_leader'];
		$user->save();
	}

	public function phoneToNumber($phone)
	{
		return str_replace('-', '', $phone);
	}

	public function sendActivationEmail(User $user)
	{
		Mail::to($user)->send(new ActivateUser($user));
	}
}
