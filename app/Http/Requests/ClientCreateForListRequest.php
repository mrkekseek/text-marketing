<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientCreateForListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstname' => 'required',
            'view_phone' => 'required',
            'phone' => 'required|digits:10|unique:clients,phone,'.(empty($this->id) ? 'null' : $this->id).',id,team_id,'.(auth()->user()->teams_id),
            'email' => 'unique:clients,email,'.(empty($this->id) ? 'null' : $this->id).',id,team_id,'.(auth()->user()->teams_id),
        ];
    }
}
