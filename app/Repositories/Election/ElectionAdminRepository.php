<?php

namespace App\Repositories\Election;

use App\Models\Meeting;

class ElectionAdminRepository implements IElectionAdminRepository
{

    /**
     * Marks the election and all motions as
     * open for voting
     * @param Meeting $meeting
     * @return Meeting
     */
    public function startVoting(Meeting $meeting)
    {
        $meeting->openVoting();
        //Mark each motion as able to be voted upon
        foreach ($meeting->motions as $m) {
            $m->openVoting();
        }
        return $meeting;
    }


    /**
     * Marks the election and all motions so that can no longer vote
     * @param Meeting $meeting
     * @return Meeting
     */
    public function endVoting(Meeting $meeting)
    {

        //Set the properties on the election object
        $meeting->closeVoting();

        //Mark each motion closed
        foreach ($meeting->motions as $m) {
            $m->closeVoting();
        }

        return $meeting;
    }


    public function releaseResults(Meeting $meeting)
    {

    }


}
