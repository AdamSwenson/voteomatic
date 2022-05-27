<?php

namespace App\Repositories\Election;

use App\Models\Meeting;

interface IElectionAdminRepository
{
    public function startVoting(Meeting $meeting);

    public function endVoting(Meeting $meeting);

    public function releaseResults(Meeting $meeting);
}
