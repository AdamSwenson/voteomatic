<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WaitlistRequest extends FormRequest
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
//            'email' => 'email|required',
//            'name' => 'required',
//            'organization' => 'nullable',
//            'notes' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
//            'email.required' => 'We need to know your e-mail address!',
//            'name.required' => 'We need to know your name!'
        ];
    }
}
