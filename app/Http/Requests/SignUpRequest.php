<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'firstname' => 'required',
            'lastname' => 'required',
            'password' => 'required',
            'view_phone' => 'required_if:plans_code,home-advisor',
            'rep' => 'required_if:plans_code,home-advisor',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'view_phone.required_if' => __('Your Cell # is required'),
            'rep.required_if' => __('HomeAdvisor Account # is required'),
        ];
    }
}
