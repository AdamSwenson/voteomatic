<?php

namespace App\Policies;

use App\Models\Election\Candidate;
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
     * Determine whether the user can view all
     * candidates associated with them.
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
     * @param \App\Models\Candidate $candidate
     * @return mixed
     */
    public function view(User $user, Candidate $candidate)
    {

        return true;

    }

    public function addToBallot(User $user){
        return true;
        //todo should check that user owns meeting
        return $user->isChair();
    }
    /**
     * Determine whether the user can create candidates.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
  //regular users need to be able to do this for write in
        return true;

//        return $user->isChair();

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
        return $user->isChair();

    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param Candidate $candidate
     * @return mixed
     */
    public function restore(User $user, Candidate $candidate)
    {
        return $user->isChair();
    }

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
}
