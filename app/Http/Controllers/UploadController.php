<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use CsvReader;
use Validator;
use App\Http\Services\UsersService;
use App\Client;
use App\Libraries\ApiValidate;
use App\Http\Requests\ClientCreateRequest;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function save(Request $request)
    {
    	$path = '';
		if ($request->file) {
			$path = $request->file->store('public/upload/temp');
		}
		return $path = str_replace('public', 'storage', $path);
    }

    public function csv(Request $request)
    {
    	$rules = [
    		'firstname' => 'required',
            'view_phone' => 'required',
            'phone' => 'required|digits:10|unique:clients,phone,'.(empty($this->id) ? 'null' : $this->id).',id,team_id,'.(auth()->user()->teams_id),
            'email' => 'unique:clients,email,'.(empty($this->id) ? 'null' : $this->id).',id,team_id,'.(auth()->user()->teams_id),
    	];

		if ($request->file) {
			$reader = CsvReader::open($request->file);
			$result = [];
			foreach ($reader->readAll() as $row) {
				$data = [
					'firstname' => trim($row[0]),
					'lastname' => trim($row[1]),
					'view_phone' => trim($row[2]),
					'email' => trim($row[3]),
				];

				$data['phone'] = UsersService::phoneToNumber($data);
				$data['team_id'] = auth()->user()->teams_id;
				$data['source'] = 'csv';
				
				$validator = Validator::make($data, $rules);
				if ( ! $validator->fails()) {
					$client = Client::create($data);
					$result[] = $client;
					$message = 'Clients was successfully saved';
				} else {
					$error = 'Some clients not save';
				}
			}
			if ( ! empty($message)) {
				$this->message($message, 'success');
			}
			if ( ! empty($error)) {
				$this->message($error);
			}
			
			return $result;
		}
    }
}
