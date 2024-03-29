<?php

namespace App\Policies;

use App\Models\RecordedVoteRecord;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecordedVoteRecordPolicy
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
     * @param  \App\Models\RecordedVoteRecord  $recordedVoteRecord
     * @return mixed
     */
    public function view(User $user, RecordedVoteRecord $recordedVoteRecord)
    {
        return $user->isChair() || $recordedVoteRecord->user_id === $user->id;
    }

//    /**
//     * Determine whether the user can create models.
//     *
//     * @param  \App\Models\User  $user
//     * @return mixed
//     */
//    public function create(User $user)
//    {
//        //dev this is handled by a method in the motion policy
//        return true;
//    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RecordedVoteRecord  $recordedVoteRecord
     * @return mixed
     */
    public function update(User $user, RecordedVoteRecord $recordedVoteRecord)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RecordedVoteRecord  $recordedVoteRecord
     * @return mixed
     */
    public function delete(User $user, RecordedVoteRecord $recordedVoteRecord)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RecordedVoteRecord  $recordedVoteRecord
     * @return mixed
     */
    public function restore(User $user, RecordedVoteRecord $recordedVoteRecord)
    {
        return $user->isChair();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RecordedVoteRecord  $recordedVoteRecord
     * @return mixed
     */
    public function forceDelete(User $user, RecordedVoteRecord $recordedVoteRecord)
    {
        return false;
    }
}
