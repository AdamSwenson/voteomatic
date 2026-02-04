<?php

namespace App\Repositories\Vote;

use App\Events\VotingOnMotionOpened;
use App\Models\Motion;
use App\Models\RecordedVoteRecord;
use App\Models\Vote;

class VoteManagementRepository implements IVoteManagementRepository
{

    public function __construct()
    {

    }

    /**
     * If the voting needs to be ended before it is closed without reporting results,
     *  this aborts the vote. It deletes all cast votes and closes the voting
     *
     * @param Motion $motion
     * @return void
     */
    public function abortVotingOnMotion(Motion $motion)
    {

        //We can only abort a vote if it is currently underway.
        //This is important because this is one of the very few
        //times we will remove vote records from the db.
        if($motion->is_voting_allowed){

            //Turn off voting
            //NB, cannot set is_complete because that will cause results
            //to be shown
            $motion->is_voting_allowed = false;
            $motion->save();
            $motion->refresh();

            //Delete all vote records
            Vote::where('motion_id', $motion->id)->delete();

            //Delete all records of who voted
            RecordedVoteRecord::where('motion_id', $motion->id)->delete();

        }


    }

    public function startVotingOnMotion(Motion $motion){

        $motion->is_voting_allowed = true;
        $motion->save();

    }


}
