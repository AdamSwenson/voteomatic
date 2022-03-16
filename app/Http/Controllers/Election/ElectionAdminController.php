<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Repositories\Election\IElectionAdminRepository;
use App\Repositories\IMeetingRepository;
use Illuminate\Http\Request;

class ElectionAdminController extends Controller
{


    /**
     * @var IElectionAdminRepository|mixed
     */
    public $electionAdminRepo;

    public function __construct()
    {
        $this->electionAdminRepo = app()->make(IElectionAdminRepository::class);
        $this->middleware('auth');
    }
    public function startVoting(Meeting $meeting)
    {
        $election = $this->electionAdminRepo->startVoting($meeting);
    return response()->json($election);
    }


    public function stopVoting(Meeting $meeting)
    {
        $election = $this->electionAdminRepo->endVoting($meeting);


         return response()->json($election);
    }


    public function releaseResults(Meeting $meeting)
    {
        $election = $this->electionAdminRepo->releaseResults($meeting);
        return response()->json($election);
    }


    public function hideResults(Meeting $meeting)
    {
        $election = $this->electionAdminRepo->hideResults($meeting);
        return response()->json($election);
    }

}
