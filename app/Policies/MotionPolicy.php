<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MotionPolicy
{
    use HandlesAuthorization;

    public function setAsCurrent(User $user, Motion $motion)
    {
//todo should this be chair only?
        $meeting = $motion->meeting;
        return $meeting->isPartOfMeeting($user);

    }

    public function markComplete(User $user, Motion $motion)
    {
//todo should this be chair only?
        $meeting = $motion->meeting;
        return $meeting->isPartOfMeeting($user);

    }

    public function secondMotion(User $user, Motion $motion)
    {
        $meeting = $motion->meeting;
        return $meeting->isPartOfMeeting($user);
    }

    public function castVoteOnMotion(User $user, Motion $motion)
    {
        $meeting = $motion->meeting;
        return $meeting->isPartOfMeeting($user);
    }


    public function viewMotionResults(User $user, Motion $motion)
    {
        $meeting = $motion->meeting;
        return $meeting->isPartOfMeeting($user) && $motion->is_complete;
    }

    /**
     * NB, this is used where the only param is a meeting object.
     * Thus it needs to be called like this:
     *         $this->authorize('viewAllMeetingMotions', [Motion::class, $meeting])
     *
     * @param User $user
     * @param Meeting $meeting
     * @return bool
     */
    public function viewAllMeetingMotions(User $user, Meeting $meeting)
    {
        return $meeting->isPartOfMeeting($user);
    }


    // ========================== ELECTION SPECIFIC

    public function createOffice(User $user, Meeting $meeting){

        return $meeting->isOwner($user);

    }


    public function castVoteForOffice(User $user, Motion $office)
    {
        $election = $office->meeting;
        return $election->isPartOfMeeting($user);
    }

    public function deleteOffice(User $user, Motion $office)
    {
        $election = $office->meeting;
        return $election->isOwner($user);
    }



    public function viewOffice(User $user, Motion $office){
        $meeting = $office->meeting;
        return ($meeting->isPartOfMeeting($user) || $meeting->isOwner($user));
    }

    /**
     * Different from regular motion in case we need
     * different sets of permissions.
     *
     * Allows when:
     *     Election is complete
     *  AND
     *     A member
     *    OR
     *     Owner
     *
     * @param User $user
     * @param Motion $motion
     * @return bool
     */
    public function viewOfficeResults(User $user, Motion $office)
    {
        $meeting = $office->meeting;
        return $office->is_complete && ($meeting->isPartOfMeeting($user) || $meeting->isOwner($user));
    }

    public function updateOffice(User $user, Motion $office){
        $meeting = $office->meeting;
        return $meeting->isOwner($user);
    }


//=========================== CRUD

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Motion $motion
     * @return mixed
     */
    public function view(User $user, Motion $motion)
    {
        $meeting = $motion->meeting;
        return $meeting->isPartOfMeeting($user);

//$meeting= $motion->meeting()->first();
//$meeting->isPartOfMeeting($user);

        return true;
    }

    public function viewAll(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isChair();

        //todo Eventually for VOT-6
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Motion $motion
     * @return mixed
     */
    public function update(User $user, Motion $motion)
    {
        return $user->isChair();

        //todo Eventually for VOT-6
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Motion $motion
     * @return mixed
     */
    public function delete(User $user, Motion $motion)
    {
        return $user->isChair();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Motion $motion
     * @return mixed
     */
    public function restore(User $user, Motion $motion)
    {
        return $user->isChair();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Motion $motion
     * @return mixed
     */
    public function forceDelete(User $user, Motion $motion)
    {
        return $user->isChair();
    }
}
