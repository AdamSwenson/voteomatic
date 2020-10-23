<?php


namespace App\Repositories;

use App\Models\Motion;
use App\Models\RecordedVoteRecord;
use App\Models\User;

/**
 * Class VoterEligibilityRepository
 * Handles determinations of whether someone has already voted
 * @package App\Repositories
 */
class VoterEligibilityRepository
{


    /**
     * Returns true if a record exists for them voting
     * on this motion
     *
     * @param Motion $motion
     * @param User $user
     * @return bool
     */
    public function hasAlreadyVoted(Motion $motion, User $user){
        $record = RecordedVoteRecord::where('motion_id', $motion->id)
            ->where('user_id', $user->id)
            ->first();

        return ! is_null($record);

    }


    public function recordVoted(Motion $motion, User $user){
        //todo make the user_id / motion_id combo a unique key so will throw error if try to insert record

        $r = new RecordedVoteRecord();
        $r->user()->associate($user);
        $r->motion()->associate($motion);
        $r->save();
    }

}
