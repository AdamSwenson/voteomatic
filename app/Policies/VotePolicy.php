<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vote;
use Illuminate\Auth\Access\HandlesAuthorization;
//use Illuminate\Support\Facades\Response;
use Illuminate\Auth\Access\Response;

class VotePolicy
{
    use HandlesAuthorization;


//
//
//    /**
//     * Determine whether the user can view any models.
//     *
//     * @param  \App\Models\User  $user
//     * @return mixed
//     */
//    public function viewAny(User $user)
//    {
//        //
//    }
//
//    /**
//     * Determine whether the user can view the model.
//     *
//     * @param  \App\Models\User  $user
//     * @param  \App\Models\vote  $vote
//     * @return mixed
//     */
//    public function view(User $user, Vote $vote)
//    {
//        //
//    }
//
//    /**
//     * Determine whether the user can create models.
//     *
//     * @param  \App\Models\User  $user
//     * @return mixed
//     */
//    public function create(User $user)
//    {
//        //
//    }
//
//    /**
//     * Determine whether the user can update the model.
//     *
//     * @param  \App\Models\User  $user
//     * @param  \App\Models\vote  $vote
//     * @return mixed
//     */
//    public function update(User $user, Vote $vote)
//    {
//        //todo decide whether should follow robz instead
//        Response::deny('Votes cannot be changed once cast.');
//
////        $moton = $vote->motion;
////
////        return $user->id === $post->user_id
////            ? Response::allow()
////            : Response::deny('You do not own this post.');
////
////        //
//    }
//
//    /**
//     * Determine whether the user can delete the model.
//     *
//     * @param  \App\Models\User  $user
//     * @param  \App\Models\vote  $vote
//     * @return mixed
//     */
//    public function delete(User $user, Vote $vote)
//    {
//        Response::deny('Votes cannot be changed once cast.');
//
//    }
//
//    /**
//     * Determine whether the user can restore the model.
//     *
//     * @param  \App\Models\User  $user
//     * @param  \App\Models\vote  $vote
//     * @return mixed
//     */
//    public function restore(User $user, Vote $vote)
//    {
//        Response::deny('Votes cannot be changed once cast.');
//        //
//    }
//
//    /**
//     * Determine whether the user can permanently delete the model.
//     *
//     * @param  \App\Models\User  $user
//     * @param  \App\Models\vote  $vote
//     * @return mixed
//     */
//    public function forceDelete(User $user, Vote $vote)
//    {
//        Response::deny('Votes cannot be changed once cast.');
//
//        //
//    }
}
