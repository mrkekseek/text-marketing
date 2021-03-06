<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeancesCreateRequest extends FormRequest
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
            'clients' => 'required|filled|array',
            'text' => 'boolean',
            'email' => 'boolean',
            'schedule' => 'boolean',
            'time' => 'array',
            'survey' => 'required',
            'company' => 'required_if:text,1',
        ];
    }
}
