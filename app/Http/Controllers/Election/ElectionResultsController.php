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
        $this->electionResultsRepo = app()->make(IElectionResultsRepository::class);
    }


    public function getResults(Motion $motion)
    {
        $this->setLoggedInUser();
        $this->authorize('viewOfficeResults', [Motion::class, $motion]);

        $out = $this->electionResultsRepo->getResultsForClient($motion);

        return response()->json($out);
    }

}
