<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Motion;
use App\Repositories\Election\IElectionRepository;
use Illuminate\Http\Request;

class ElectionResultsController extends Controller
{
    /**
     * @var IElectionRepository|mixed
     */
    public $electionRepo;

    public function __construct()
    {

        $this->middleware('auth');
//        $this->middleware('vote-eligibility');

        //   $this->middleware('previously-voted');
//        $this->middleware('motion-closed');

        //todo Add restriction to chair

        $this->electionRepo = app()->make(IElectionRepository::class);
    }


    public function getResults(Motion $motion)
    {
        $results = $this->electionRepo->getResults($motion, true);

        $out = [];
//dd($results);
        foreach($results as $result){

            $pct = $motion->totalVotesCast > 0 ? $result->totalVotesReceived / $motion->totalVotesCast : 0;

            $out[] = [
                'motionId' => $motion->id,
                'candidateId' => $result->id,
                'candidateName' => $result->name,
                'voteCount' => $result->totalVotesReceived,
                'pctOfTotal' => $pct
            ];
        }

//        return ($out);


//        $out = [
////            'winners' => $this->electionRepo->getWinners($motion, true),
////            'counts' => $this->electionRepo->getResults($motion, true),
//            'shares' => $this->electionRepo->getVotePercentages($motion)
//        ];

        return response()->json($out);

    }

}
