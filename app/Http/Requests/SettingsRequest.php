<?php

namespace App\Http\Requests;

use App\Models\Meeting;
use App\Models\SettingStore;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class SettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();
        $settingStore = SettingStore::find($this->id);
        $meeting = $settingStore->meeting;
//        $meeting = Meeting::find($this->meetingId);
        foreach ($this->settings as $name => $v) {
            if (in_array($name, SettingStore::CHAIR_ONLY_SETTINGS)) {
                if (!$meeting->isOwner($user)) return false;
            }
        }

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
            'meetingId' => 'required',
            'settings' => 'required'
        ];
    }
}
