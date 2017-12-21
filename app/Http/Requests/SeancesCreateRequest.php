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
            'date' => 'date',
            'time' => 'date',
            'survey' => 'required',
            'company' => 'required',
        ];
    }
}
