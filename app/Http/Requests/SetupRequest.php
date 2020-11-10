<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetupRequest extends FormRequest
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
            'sub_type_id' => 'required|integer',

            'business_name' => 'required|string',
            'business_email' => 'required|email|unique:business',
            'business_phone' => 'required|string|required',
            'business_address' => 'required|string|required',
            'business_country' => 'required|string|required',
            'business_timezone' => 'required|string',
            'business_default_language' => 'required|string',
            'business_currency_symbol' => 'required|string',

            'user_fullname' => 'required|string',
            'user_phone' => 'required|string',
            'user_address' => 'required|string',
            'user_city' => 'required|string',
            'user_country' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
        ];
    }
}
