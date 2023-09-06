<?php

namespace App\Http\Requests\Partner;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name'     => 'required|max:25',
            'email'    => 'required|email|unique:partners,email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'phone'    => 'required|digits:10|unique:partners,phone|unique:users,mobile',
            'type'     => 'required',
            'city'     => 'required',
            'state'    => 'required',
            'pin'      => 'required|max:6',
        ];
    }
}
