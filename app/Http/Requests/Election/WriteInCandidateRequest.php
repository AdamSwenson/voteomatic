<?php

namespace App\Http\Requests\Election;

use App\Exceptions\BadWriteInAttempt;
use Illuminate\Foundation\Http\FormRequest;

class WriteInCandidateRequest extends FormRequest
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
        //dev Validation will be handled by middleware so that we can use
        // the messaging system with an exception
        return [
//            'first_name' => ['required'],
//            'last_name' => ['required']
            ];
    }

}
