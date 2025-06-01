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
        } catch (ModelNotFoundException $e) {
            $userSettings = new SettingStore();
            $userSettings->user()->associate($user);
            $userSettings->meeting()->associate($meeting);
            $userSettings->save();
        }

        $meetingMaster = $meeting->getMasterSettingStore();

        if (!is_null($meetingMaster) && !is_null($meetingMaster->settings)) {

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
        $validSettings = $meeting->is_election ? SettingStore::VALID_ELECTION_SETTINGS : SettingStore::VALID_MEETING_SETTINGS;
        foreach ($validSettings as $setting) {
            //dev If the default value is false it will not store the setting in the db
            $defaultVal = SettingStore::SETTINGS_DISPLAY_PROPERTIES[$setting]['default'];
            $settings->setSetting($setting, $defaultVal);
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

    /**
     * When a meeting/election is duplicated, this copies all the settings from
     * the original to the new meeting
     *
     * @param Meeting $originalMeeting
     * @param Meeting $newMeeting
     * @return void
     */
    public function duplicateSettingStores(Meeting $originalMeeting, Meeting $newMeeting)
    {
        $ogMaster = $originalMeeting->settingStore()->where('is_meeting_master', true)->first();
        $newMaster = $newMeeting->settingStore()->where('is_meeting_master', true)->first();

        //Make sure the new meeting has settings
        if (is_null($newMaster)) {
            $newMaster = $this->createMeetingMaster($newMeeting);
        }

        if (!is_null($ogMaster)) {
            //Copy the master settings. If there wasn't master settings for the original
            //the new meeting will just have the defaults created above.
            $newMaster->settings = $ogMaster->settings;
            $newMaster->is_universal = $ogMaster->is_universal;
            $newMaster->applies_to_all_members = $ogMaster->applies_to_all_members;
            $newMaster->save();
        }

        //Create settings stores for users
        $ogUserSettings = $originalMeeting->settingStore()->where('is_meeting_master', null)->get();
        foreach ($ogUserSettings as $ogUserSetting) {
            $settings = SettingStore::create();
            $settings->user()->associate($ogUserSetting->user);
            $settings->meeting()->associate($newMeeting);
            $settings->settings = $ogUserSetting->settings;
            $settings->save();
        }

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
