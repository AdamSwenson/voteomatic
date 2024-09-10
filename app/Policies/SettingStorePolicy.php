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

    public function view(User $user, Meeting $meeting)
    {
  return $meeting->isPartOfMeeting($user);

    }

//    /**
//     * Whether someone can view the settings associated with a
//     * person for a given meeting
//     *
//     * @param User $user
//     * @param SettingStore $settings
//     * @return mixed
//     */
//    public function viewUser(User $user, Meeting $meeting)
//    {
//        return
//    }
//
    /**
     * ONly the chair or person setting up the meeting
     * will need to access the meeting master settings directly
     *
     * @param User $user
     * @param Meeting $meeting
     */
    public function viewMaster(User $user, Meeting $meeting){
       return $meeting->isOwner($user);
    }

    /**
     * This just checks the user's permissions.
     *
     * Whether the specific setting being accessed is allowed will
     * be checked in the SettingsRequest
     *
     * @param User $user
     * @param SettingStore $settings
     * @return bool
     */
    public function update(User $user, SettingStore $settings)
    {

//        $meeting = Meeting::find($settings->meeting_id);
        $meeting = $settings->meeting;


        return $user->is($settings->user()->first()) || $meeting->isOwner($user);
    }

    public function delete(User $user, SettingStore $settings)
    {

    }

}
