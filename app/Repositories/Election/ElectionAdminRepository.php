<?php

namespace App\Repositories\Election;

use App\Models\Meeting;

class ElectionAdminRepository implements IElectionAdminRepository
{

    public function startVoting(Meeting $meeting)
    {
$meeting->openVoting();
return $meeting;
    }


    public function endVoting(Meeting $meeting)
    {

        $meeting->closeVoting();
    return $meeting;
    }


    public function releaseResults(Meeting $meeting)
    {

    }


}
