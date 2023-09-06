<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FactoryCityStoreRequest extends FormRequest
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
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'city_id' => 'city',
            'space_id' => 'space'
        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'space_id' => ['required','exists:spaces,id',],
            'city_id' => ['required', Rule::unique('factory_cities')->where(function ($query) {
                return $query->where('space_id', $this->space_id);
            })],
            'value' => ['required',]
        ];
    }
}
