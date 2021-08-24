<?php

namespace App\Repositories;

use App\Models\Meeting;
use App\Models\SettingStore;

class SettingsRepository
{


    /**
     * There should be only one meeting master object
     * per meeting. Thus everything should go through this method.
     *
     * @param Meeting $meeting
     */
    public function createMeetingMaster(Meeting $meeting)
    {
        $mm = $meeting->settingStore()->where('is_meeting_master', true)->first();

        if (!is_null($mm)) return $mm;

        $settings = SettingStore::create();
        $settings->is_meeting_master = true;
        //NB, no user is set
        $settings->meeting()->associate($meeting);
        $settings->save();
        return $settings;
    }

    public function updateMeetingMaster(Meeting $meeting, $settingName, $value){
        $ss = $meeting->getMasterSettingStore();
        $ss->setSetting($settingName, $value);
        $ss->save();
    }


    /**
     * We'll sometimes need to change a setting which covers everyone,
     * this takes all user setting objects and updates them to the values
     * set on the meeting master object
     *
     * @param Meeting $meeting
     */
    public function syncSettingsToMeetingMaster(Meeting $meeting)
    {

        $mm = $meeting->getMasterSettingStore();
        if(! is_null($mm)){
            foreach($meeting->settingStore()->get() as $settingStore){
                $settingStore->settings = $mm->settings;
                $settingStore->save();
            }

        }


    }


    public function createSettingStore(Meeting $meeting, User $user, $request)
    {

        $settings = SettingStore::create($request->except('meetingId'));
        $settings->user()->associate($user);
        $settings->meeting()->associate($meeting);

        $settings->save();

        return $settings;

    }

//
//    /**
//     * Updates a setting for all users who are part of a meeting
//     *
//     * NB, this only covers currently existing setting objects
//     * NB, checks for permissions should happen elsewhere
//     *
//     * @param Settings $settings
//     */
//    public function updateUniversalSettings(Meeting $meeting, $settingName, $newValue)
//    {
//        $settings = $meeting->settingStore()->get();
//        foreach ($settings as $setting) {
//            $setting->setSetting($settingName, $newValue);
//        }
//
//    }
}
