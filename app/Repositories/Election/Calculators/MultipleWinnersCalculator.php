<?php


namespace App\Repositories\Election\Calculators;


use App\Models\Election\Candidate;
use App\Models\Motion;

/**
 * Class MultipleWinnersCalculator
 *
 * Determines winners when the top n vote getters are winners
 *
 * @package App\Repositories\Election\Calculators
 */
class MultipleWinnersCalculator extends IResultsCalculator
{

    public function __construct(Motion $motion)
    {
        parent::__construct($motion);
    }


//    public function getWinners()
//    {
//    }


    public function isWinner(Candidate $candidate)
    {

     //   if ($this->isRunoffParticipant($candidate)) return false;

//If they are not tied, then we can just take the top folks
        $topIds = $this->results->pluck('ids')->slice(0, $this->motion->max_winners);

        return $topIds->contains($candidate->id);

    }

    /**
     * Determine whether the candidate must participate in a runoff
     * @param Candidate $candidate
     * @return bool
     */
    public function isRunoffParticipant(Candidate $candidate)
    {
        //Grab everyone that has the same score as our candidate
        $sameScore = $this->results->where('totalVotesReceived', $candidate->totalVotesReceived)->all();

        //Get the ids of those within the allowed number of winners
        $topIds = $this->results->slice(0, $this->motion->max_winners)->pluck('id');

        //If there's only one person with the score, there's no tie
        if (sizeof($sameScore) === 1) return false;

        foreach ($sameScore as $candidate) {
            if ($topIds->contains($candidate->id)) {
                //If any of the candidates with the same score
                //are within the range defined by max winners, there will
                //need to be a runoff
                return true;
            }
        }

        //We end up here if there was a tie but no tied candidate could've been the winner
        return false;

    }


}
