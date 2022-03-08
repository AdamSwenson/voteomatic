<?php


namespace App\Repositories\Election\Calculators;


use App\Models\Election\Candidate;
use App\Models\Motion;
use App\Repositories\Election\Calculators\IResultsCalculator;

/**
 * Class MajorityWinnerCalculator
 *
 * Determines winners when the winner must win more than
 * 50% of the vote
 *
 * dev currently unused
 *
 * @package App\Repositories\Election
 */
class PropositionCalculator extends IResultsCalculator
{


    public mixed $voteThreshold;

    public function __construct(Motion $motion)
    {
        parent::__construct($motion);
        $this->voteThreshold = $motion->requires;
    }

//    public function getWinners(){}


    /**
     * Returns true only if the proposition received more than the vote threshold
     *
     * @param Candidate $candidate
     * @return bool
     */
    public function isWinner()
    {
//        return $candidate->is($this->results[0]);// && $candidate->getShareOfVotesCast() > 0.5;
         return $this->motion->passed;

//        return  $candidate->getShareOfVotesCast() > 0.5;

    }


//    /**
//     * Determine whether the candidate must participate in a runoff
//     * @param Candidate $candidate
//     * @return bool
//     */
//    public function isRunoffParticipant(Candidate $candidate)
//    {
//        //Grab everyone that has the same score as our candidate
//        $sameScore = $this->results->where('totalVotesReceived', $candidate->totalVotesReceived)->all();
//
//        //Get the ids of those within the allowed number of winners
//        $topIds = $this->results->slice(0, $this->motion->max_winners)->pluck('id');
//
//        //If there's only one person with the score, there's no tie
//        if (sizeof($sameScore) === 1) return false;
//
//        foreach ($sameScore as $candidate) {
//            if ($topIds->contains($candidate->id)) {
//                //If any of the candidates with the same score
//                //are within the range defined by max winners, there will
//                //need to be a runoff
//                return true;
//            }
//        }
//
//        //We end up here if there was a tie but no tied candidate could've been the winner
//        return false;
//
//    }

}
