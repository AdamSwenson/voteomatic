<?php

namespace App\Policies;

use App\Models\Election\PoolMember;
use App\Models\Motion;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PoolMemberPolicy
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
     * Determine whether the user can create poolMembers.
     * To do so, they must be the owner of the meeting
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function createPoolMember(User $user, Motion $motion)
    {
        $meeting = $motion->meeting;
        return $meeting->isOwner($user);
    }

    /**
     * Determine whether the user can view all
     * poolMembers for the office.
     *
     * This allows either the owner or a member of the meeting
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function viewCandidatePool(User $user, Motion $motion)
    {
        $meeting = $motion->meeting;
        return $meeting->isPartOfMeeting($user) || $meeting->isOwner($user);
//        return $user->isChair();
    }


    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\PoolMember $poolMember
     * @return mixed
     */
    public function view(User $user, PoolMember $poolMember)
    {
        return $user->isChair();

    }



    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\PoolMember $poolMember
     * @return mixed
     */
    public function update(User $user, PoolMember $poolMember)
    {
        return $user->isChair();

//        return $user->is($poolMember->getOwner());

        //dd($user->is_admin);

        //Only administrators should be able to mess with poolMembers
//        return $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param PoolMember $poolMember
     * @return mixed
     */
    public function delete(User $user, PoolMember $poolMember)
    {
        return $user->isChair();

    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param PoolMember $poolMember
     * @return mixed
     */
    public function restore(User $user, PoolMember $poolMember)
    {
        return $user->isChair();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param PoolMember $poolMember
     * @return mixed
     */
    public function forceDelete(User $user, PoolMember $poolMember)
    {
        return $user->isChair();

    }
}
