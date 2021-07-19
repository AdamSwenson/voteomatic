<?php

namespace App\Policies;

use App\Models\Election\Candidate;
use App\Models\Election\PoolMember;
use App\Models\Motion;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CandidatePolicy
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



    /**
     * Policy for adding official candidate to ballot
     *
     * @param User $user
     * @param Motion $motion
     * @return bool|mixed
     */
    public function addToBallot(User $user, Motion $motion){
        $meeting = $motion->meeting;
        return $meeting->isOwner($user);
    }


    /**
     * Determine whether the user can create a write in candidate.
     * This allows all members of the meeting (i.e., election) to
     * create a candidate.
     *
     * It denies all non-members, including the owner.
     *
     * @param \App\Models\User $user
     * @param Motion $motion
     * @return mixed
     */
    public function addWriteInCandidate(User $user, Motion $motion)
    {
        $meeting = $motion->meeting;
        return $meeting->isPartOfMeeting($user);
    }

//    /**
//     * Determine whether the user can create candidates.
//     *
//     * @param \App\Models\User $user
//     * @return mixed
//     */
//    public function create(User $user)
//    {
//  //regular users need to be able to do this for write in
//        return true;
////        return $user->isChair();
//    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param Candidate $candidate
     * @return mixed
     */
    public function forceDelete(User $user, Candidate $candidate)
    {
        return $user->isChair();

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Candidate $candidate
     * @return mixed
     */
    public function update(User $user, Candidate $candidate)
    {
        return $user->isChair();

//        return $user->is($candidate->getOwner());

        //dd($user->is_admin);

        //Only administrators should be able to mess with candidates
//        return $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param Candidate $candidate
     * @return mixed
     */
    public function delete(User $user, Candidate $candidate)
    {
        $motion = $candidate->motion;
        $meeting = $motion->meeting;
        return $meeting->isOwner($user);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param Candidate $candidate
     * @return mixed
     */
    public function restore(User $user, Motion $motion)
    {
        $meeting = $motion->meeting;
        return $meeting->isOwner($user);
    }


    /**
     * Determine whether the user can view the candidates
     * for an office. Decides on basis of whether they are a member
     * of the meeting
     *
     * @param \App\Models\User $user
     * @param Motion $motion
     * @return mixed
     */
    public function viewOfficialCandidates(User $user, Motion $motion)
    {
//        $motion = $candidate->motion;
        $meeting = $motion->meeting;
        return $meeting->isPartOfMeeting($user);
    }


    /**
     * Determine whether the user can view all
     * candidates associated with them.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function viewIndex(User $user, Motion $motion)
    {
        return $user->isChair();
    }

}
