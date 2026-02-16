<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoteValidationRequest extends FormRequest
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

    public function messages()
    {
     return [
         'receipt.required' => "Please enter a receipt"
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
            'receipt' => 'required',
        ];
    }
}
