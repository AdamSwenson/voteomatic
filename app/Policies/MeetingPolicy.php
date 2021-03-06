<?php

namespace App\Policies;

use App\Exceptions\IneligibleMeetingCreator;
use App\Models\User;
use App\Models\Meeting;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeetingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meeting  $meeting
     * @return mixed
     */
    public function view(User $user, Meeting $meeting)
    {
        // todo this may eventually get a check for whether the user is associated with the meeting. Depends on how the order of adding folks to the meeting happens after LTI launch
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        dd($user->is_admin);

        //Only administrators should be able to mess with meetings
        if(! $this->is_admin){
            throw IneligibleMeetingCreator;
        }

        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meeting  $meeting
     * @return mixed
     */
    public function update(User $user, Meeting $meeting)
    {
dd($user->is_admin);


        //Only administrators should be able to mess with meetings
        return $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meeting  $Meeting
     * @return mixed
     */
    public function delete(User $user, Meeting $meeting)
    {

        //Only administrators should be able to mess with meetings
        return $user->is_admin;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meeting  $Meeting
     * @return mixed
     */
    public function restore(User $user, Meeting $meeting)
    {

        //Only administrators should be able to mess with meetings
        return $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meeting  $Meeting
     * @return mixed
     */
    public function forceDelete(User $user, Meeting $meeting)
    {

        //Only administrators should be able to mess with meetings
        return $user->is_admin;
    }
}
