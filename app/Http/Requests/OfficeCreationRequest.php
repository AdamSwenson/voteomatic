<?php

namespace App\Http\Requests;

use App\Models\Meeting;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class OfficeCreationRequest
 *
 * This is used to add a new office to an election
 *
 * @package App\Http\Requests
 */
class OfficeCreationRequest extends FormRequest
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
     * Returns the meeting object representing the election
     */
    public function getElection(){
//        return Meeting::find($this->meetingId);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//            'meetingId' => ['required']
        ];
    }
}
