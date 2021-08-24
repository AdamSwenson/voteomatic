<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\SettingStore;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingStorePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function setMembersMakeMotions(User $user)
    {
        return $user->isChair();
    }


    public function create(User $user, Meeting $meeting)
    {
        return $meeting->isPartOfMeeting($user);
    }

    public function view(User $user, SettingStore $settings)
    {
return $settings->user->is($user);
    }


    /**
     * This just checks the user's permissions.
     *
     * Whether the setting being accessed is allowed will
     * be checked in the SettingsRequest
     *
     * @param User $user
     * @param SettingStore $settings
     * @return bool
     */
    public function update(User $user, SettingStore $settings)
    {

        $meeting = Meeting::find($settings->meeting_id);
        //settings->meeting()->first()


        return $user->is($settings->user()->first()) || $meeting->isOwner($user);
    }

    public function delete(User $user, SettingStore $settings)
    {

    }

}
