<?php

namespace App\Policies;

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
     * Determine whether the user can view all
     * poolMembers associated with them.
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
     * @param \App\Models\PoolMember $poolMember
     * @return mixed
     */
    public function view(User $user, PoolMember $poolMember)
    {
        return $user->isChair();


    }

    /**
     * Determine whether the user can create poolMembers.
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
