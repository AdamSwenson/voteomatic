<?php

namespace App\Repositories\Election;

use App\Models\Meeting;
use App\Models\SettingStore;
use App\Repositories\ISettingsRepository;
use App\Repositories\SettingsRepository;

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


    /**
     * Makes the election results available to all users
     * @param Meeting $meeting
     * @return Meeting
     */
    public function releaseResults(Meeting $meeting)
    {
        //dev This is probably problematic since results and closed are 2 separate phases
        $meeting->releaseElectionResults();

        //dev Added VOT-288
        $this->purgeAndPermanentlyLockElection($meeting);

        return $meeting;
    }


    /**
     * Makes election results no longer visible to anyone except the election admin
     * @param Meeting $meeting
     * @return Meeting
     */
    public function hideResults(Meeting $meeting)
    {
        $meeting->hideElectionResults();
        return $meeting;
    }

    /**
     * To maximally provide anonymity for elections, this prevents any votes from being cast
     * and purges the records of who voted. It does not affect the Votes. Results can still be
     * accessed.
     * @param Meeting $meeting
     * @return void
     */
    public function purgeAndPermanentlyLockElection(Meeting $meeting){
        //Check that this is allowed for the election
        $settings = $meeting->getMasterSettingStore();
        if($settings->getSetting('permalock_election') !== true) return false;

        $meeting->is_permalocked = true;


        foreach($meeting->motions as $motion){
            $motion = $motion;

            foreach ($motion->recordedVoteRecord as $record) {
                $record->delete();
            }
        }

        $meeting->save();

        return $meeting;
    }

}
