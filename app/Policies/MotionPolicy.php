<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MotionPolicy
{
    use HandlesAuthorization;

    public function setAsCurrent(User $user, Motion $motion){
//todo should this be chair only?
        $meeting = $motion->meeting;
        return $meeting->isPartOfMeeting($user);

    }

    public function markComplete(User $user, Motion $motion){
//todo should this be chair only?
        $meeting = $motion->meeting;
        return $meeting->isPartOfMeeting($user);

    }

    public function secondMotion(User $user, Motion $motion){
        $meeting = $motion->meeting;
        return $meeting->isPartOfMeeting($user);
    }

    public function castVoteOnMotion(User $user, Motion $motion){
        $meeting = $motion->meeting;
        return $meeting->isPartOfMeeting($user);
    }


public function viewMotionResults(User $user, Motion $motion){
    $meeting = $motion->meeting;
    return $meeting->isPartOfMeeting($user);

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

//=========================== CRUD

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Motion  $motion
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

    public function viewAll(User $user){
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
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
     * @param  \App\Models\User  $user
     * @param  \App\Models\Motion  $motion
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
     * @param  \App\Models\User  $user
     * @param  \App\Models\Motion  $motion
     * @return mixed
     */
    public function delete(User $user, Motion $motion)
    {
        return $user->isChair();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Motion  $motion
     * @return mixed
     */
    public function restore(User $user, Motion $motion)
    {
        return $user->isChair();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Motion  $motion
     * @return mixed
     */
    public function forceDelete(User $user, Motion $motion)
    {
        return $user->isChair();
    }
}