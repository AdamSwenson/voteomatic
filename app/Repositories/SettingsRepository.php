<?php

namespace App\Repositories;

use App\Models\Meeting;
use App\Models\SettingStore;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SettingsRepository implements ISettingsRepository
{


    /**
     * Loads the user's personalized settings (if any), then
     * copies in the meeting master settings to yield an object
     * which can be returned to the client.
     * NB, it doesn't save the updated model to the database
     * @param Meeting $meeting
     * @param User $user
     * @return SettingStore
     */
    public function getConsolidatedSettings(Meeting $meeting, User $user)
    {
        //We start by looking up the store for the user.
        //If one doesn't exist, we create it
        try {
            $userSettings = SettingStore::where('meeting_id', $meeting->id)
                ->where('user_id', $user->id)
                ->firstOrFail();
        }catch (ModelNotFoundException $e){
            $userSettings = new SettingStore();
            $userSettings->user()->associate($user);
            $userSettings->meeting()->associate($meeting);
            $userSettings->save();
        }

        $meetingMaster = $meeting->getMasterSettingStore();

        if (!is_null($meetingMaster) && ! is_null($meetingMaster->settings)) {

            //overwrite any values
            foreach ($meetingMaster->settings as $k => $v) {
                $userSettings->setSetting($k, $v);
            }
        }
        $userSettings->save();
//        $userSettings->fresh();
        return $userSettings;

    }

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

        //Add null settings so that can use the keys
        //on client side
        foreach(SettingStore::VALID_SETTINGS as $setting){
            $settings->setSetting($setting, null);
        }

        $settings->save();
        return $settings;
    }

    public function updateMeetingMaster(Meeting $meeting, $settingName, $value)
    {
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
        if (!is_null($mm)) {
            foreach ($meeting->settingStore()->get() as $settingStore) {
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
