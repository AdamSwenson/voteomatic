<?php

namespace App\Repositories;

use App\Models\Meeting;

interface ISettingsRepository
{
//    /**
//     * Loads the user's personalized settings (if any), then
//     * copies in the meeting master settings to yield an object
//     * which can be returned to the client.
//     * NB, it doesn't save the updated model to the database
//     * @param Meeting $meeting
//     * @param User $user
//     */
//    public function getConsolidatedSettings(Meeting $meeting, User $user);
//
//    /**
//     * There should be only one meeting master object
//     * per meeting. Thus everything should go through this method.
//     *
//     * @param Meeting $meeting
//     */
//    public function createMeetingMaster(Meeting $meeting);
//
//    public function updateMeetingMaster(Meeting $meeting, $settingName, $value);
//
//    /**
//     * We'll sometimes need to change a setting which covers everyone,
//     * this takes all user setting objects and updates them to the values
//     * set on the meeting master object
//     *
//     * @param Meeting $meeting
//     */
//    public function syncSettingsToMeetingMaster(Meeting $meeting);
//
//    public function createSettingStore(Meeting $meeting, User $user, $request);
}
