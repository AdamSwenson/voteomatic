<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Motion;
use App\Repositories\Election\Calculators\ResultsCalculatorFactory;
use App\Repositories\Election\IElectionRepository;
use App\Repositories\Election\IElectionResultsRepository;
use Illuminate\Http\Request;

class ElectionResultsController extends Controller
{
    /**
     * @var IElectionRepository|mixed
     */
    public $electionResultsRepo;

    public function __construct()
    {

        $this->middleware('auth');
//        $this->middleware('vote-eligibility');

        //   $this->middleware('previously-voted');
//        $this->middleware('motion-closed');

        //todo Add restriction to chair

        $this->electionResultsRepo = app()->make(IElectionResultsRepository::class);
    }


    public function getResults(Motion $motion)
    {
        //todo check that election has closed

        $this->setLoggedInUser();

        $out = $this->electionResultsRepo->getResultsForClient($motion);

        return response()->json($out);

    }

}
