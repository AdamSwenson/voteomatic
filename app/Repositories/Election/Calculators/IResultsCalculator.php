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
     * Collection of candidates who must participate in runoff
     * @var \Illuminate\Support\Collection
     */
    public $inRunoff;

    /**
     * Will hold a collection of candidate objects
     * in descending order of votes received
     */
    public $results;

    /**
     * For calculators which recognize multiple winners, those
     * winners are stored in this collection.
     * Not used by all calculators.
     * @var \Illuminate\Support\Collection
     */
    public $winners;


    public function __construct(Motion $motion)
    {
        $this->inRunoff = collect();
        $this->winners = collect();
        $this->motion = $motion;
        $this->loadResults($motion);
    }

    /**
     * Returns a boolean to indicate whether the given candidate has won
     * the given office under the criteria for victory
     *
     * @param Candidate $candidate
     * @return mixed
     */
    abstract public function isWinner(Candidate $candidate);

    /**
     * Determine whether the candidate must participate in a runoff
     * @param Candidate $candidate
     * @return bool
     */
    abstract public function isRunoffParticipant(Candidate $candidate);


    /**
     * Loads and internally stores a collection of candidate objects, in descending order
     * of votes received.
     *
     * @param Motion $motion
     * @return \Illuminate\Support\Collection
     */
    public function loadResults(Motion $motion)
    {

        $this->results = collect($motion->candidates)->sortByDesc('totalVotesReceived');

        //The keys of the results array will not be in
        //index order (i.e., 0, 1, 2...). This is because after sorting
        //they still have their original index as keys. Thus if try to get by
        //index $this->results[$i], you will get the candidate who originally had that index
        //not the one after sorting. Thus we re-create the array/collection with the expected
        //keys. There's probably a more elegant way of doing this, but fuck it.
        $r = [];
        foreach ($this->results as $k => $v) {
            $r[] = $v;
        }
        $this->results = collect($r);
    }


    /**
     * Returns all candidates with the same score as the provided provided
     * This includes the provided candidate
     */
    public function getTies(Candidate $candidate)
    {
        return $this->results->where('totalVotesReceived', $candidate->totalVotesReceived)->all();
    }


}
