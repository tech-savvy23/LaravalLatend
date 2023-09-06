<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
        $validation = is_numeric(request('username')) ? 'min:10' : 'email';

        return [
            'username'=> "required|{$validation}",
            'password'=> 'required|min:8',
        ];
    }
}
