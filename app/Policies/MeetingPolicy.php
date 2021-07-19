<?php

namespace App\Policies;

use App\Exceptions\IneligibleMeetingCreator;
use App\Models\User;
use App\Models\Meeting;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeetingPolicy
{
    use HandlesAuthorization;

//    /**
//     * While this might more naturally be part of the motion policy,
//     * it is here since the request for all motions gets a meeting object
//     * as argument.
//     *
//     * @param User $user
//     * @param Meeting $meeting
//     * @return bool
//     */
//    public function viewAllMeetingMotions(User $user, Meeting $meeting)
//    {
//        return $meeting->isPartOfMeeting($user);
//    }

    /**
     * Used to restrict access to a meeting's owner
     *
     * @param User $user
     * @param Meeting $meeting
     */
public function ownerOnly(User $user, Meeting $meeting){
    return $meeting->isOwner($user);
}

    /**
     * Determine whether the user can view all
     * meetings associated with them.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function viewIndex(User $user)
    {
        return $user->isChair();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Meeting $meeting
     * @return mixed
     */
    public function view(User $user, Meeting $meeting)
    {
        // todo this may eventually get a check for whether the user is associated with the meeting. Depends on how the order of adding folks to the meeting happens after LTI launch
        return true;

        return $user->isChair() || sizeof($meeting->users()->where('id', $user->id)->first()) > 0;
    }

    /**
     * Determine whether the user can create meetings.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isChair();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Meeting $meeting
     * @return mixed
     */
    public function update(User $user, Meeting $meeting)
    {
        return $meeting->isOwner($user);
        return $user->is($meeting->getOwner());

        //dd($user->is_admin);

        //Only administrators should be able to mess with meetings
//        return $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param Meeting $meeting
     * @return mixed
     */
    public function delete(User $user, Meeting $meeting)
    {
        return $meeting->isOwner($user);
        return $user->is($meeting->getOwner());

        //Only administrators should be able to mess with meetings
//        return $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param Meeting $meeting
     * @return mixed
     */
    public function restore(User $user, Meeting $meeting)
    {
        return $meeting->isOwner($user);
        return $user->is($meeting->getOwner());

        //Only administrators should be able to mess with meetings
        return $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param Meeting $meeting
     * @return mixed
     */
    public function forceDelete(User $user, Meeting $meeting)
    {
        return $meeting->isOwner($user);
        return $user->is($meeting->getOwner());

        //Only administrators should be able to mess with meetings
        return $user->is_admin;
    }

    // ELECTION SPECIFIC =========================================
    /**
     * Determine whether the user can create meetings.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function createElection(User $user)
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param Meeting $election
     * @return mixed
     */
    public function deleteElection(User $user, Meeting $election)
    {
        return $election->isOwner($user);
    }


    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Meeting $election
     * @return mixed
     */
    public function updateElection(User $user, Meeting $election)
    {
        return $election->isOwner($user);
    }
}
