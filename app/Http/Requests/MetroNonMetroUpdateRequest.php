<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MetroNonMetroUpdateRequest extends FormRequest
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
            'space_id' => ['required','exists:spaces,id',],
            'type' => ['required', Rule::unique('metro_and_non_metros')->where(function ($query) {
                return $query->where('space_id', $this->space_id)->where('id', '<>',request('metro_and_non_metro'));
            })],
            'value' => ['required',]
        ];
    }
}
