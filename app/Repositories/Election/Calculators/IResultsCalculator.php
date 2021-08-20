<?php


namespace App\Repositories\Election\Calculators;


use App\Models\Election\Candidate;
use App\Models\Motion;

abstract class IResultsCalculator
{
    /**
     * @var Motion
     */
    public $motion;

    /**
     * Will hold a collection of candidate objects
     * in descending order of votes received
     */
    public $results;


    public function __construct(Motion $motion){
        $this->motion = $motion;
        $this->loadResults($motion);
    }


    /**
     * Loads and internally stores a collection of candidate objects, in descending order
     * of votes received.
     *
     * @param Motion $motion
     * @return \Illuminate\Support\Collection
     */
    public function loadResults(Motion $motion)
    {
        $k = $motion->candidates()->get();
        $this->results = collect($motion->candidates)
            ->sortByDesc('totalVotesReceived');
    }


//    /**
//     * Returns the candidate objects for those candidates
//     * who have won by the relevant metric
//     *
//     * @param Motion $motion
//     * @return mixed
//     */
//    abstract public function getWinners(Motion $motion);


    /**
     * Returns a boolean to indicate whether the given candidate has won
     * the given office under the criteria for victory
     *
     * @param Candidate $candidate
     * @return mixed
     */
    abstract public function isWinner(Candidate $candidate);

}
