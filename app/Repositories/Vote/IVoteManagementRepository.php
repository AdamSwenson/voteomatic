<?php

namespace App\Repositories\Vote;

use App\Models\Motion;

interface IVoteManagementRepository
{
    /**
     * If the voting needs to be ended before it is closed without reporting results,
     *  this aborts the vote. It deletes all cast votes and closes the voting
     *
     * @param Motion $motion
     * @return void
     */
    public function abortVotingOnMotion(Motion $motion);

    public function startVotingOnMotion(Motion $motion);
}
