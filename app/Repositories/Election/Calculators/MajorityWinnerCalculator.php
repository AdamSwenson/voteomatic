<?php


namespace App\Repositories\Election\Calculators;


use App\Models\Election\Candidate;
use App\Models\Motion;
use App\Repositories\Election\Calculators\IResultsCalculator;

/**
 * Class MajorityWinnerCalculator
 *
 * Used for elections where only one person can win
 *
 * Determines winners when the winner must win more than
 * 50% of the vote.
 *
 * Lists n candidates in runoff where the top n candidates whose votes total more
 * than 50%
 *
 * @package App\Repositories\Election
 */
class MajorityWinnerCalculator extends IResultsCalculator
{

    /**
     * The number of votes received by the candidate listed first in results
     * @var int
     */
    public $topVoteCount;

    public function __construct(Motion $motion)
    {
        //This creates the list of candidates in descending order stored
        //in results
        parent::__construct($motion);

        $this->topVoteCount = $this->results[0]->totalVotesReceived;
        $this->constructRunoffList();
    }

    /**
     * Returns true only if the candidate received the highest number of votes
     * and their total comprised MORE THAN 50% of votes cast.
     *
     * @param Candidate $candidate
     * @return bool
     */
    public function isWinner(Candidate $candidate)
    {
        return $candidate->getShareOfVotesCast() > 0.5;
    }


    /**
     * Determine whether the candidate must participate in a runoff.
     *
     * This checks whether a candidate is in a runoff in two stages.
     * First, everyone with the same score as the top vote-getter is in the runoff.
     * Second, we descend through the totals, adding them up, until we get to
     * greater than 50%. At that point everyone else is not in the runoff.
     *
     * @param Candidate $candidate
     * @return bool
     */
    public function isRunoffParticipant(Candidate $candidate)
    {
        //This may be called on our winner
        if ($this->isWinner($candidate)) return false;

        //First we check to see if there is a tie at the top. We need to do this
        //because the next check, where we add up totals until we get to 50%+
        //could fail if there is 3-way tie.
//        if ($this->topVoteCount === $candidate->getVoteTotal()) return true;

        //Up to this point is covered by the test isRunoffParticipantWhenMultipleTiesAtTopInPlurality
        return $this->inRunoff->pluck('id')->contains($candidate->id);
//
//        $inRunoff = [];
//        $shareTotal = 0;
//
//        $i = 0;
//        while ($shareTotal < 0.5) {
//            $c = $this->results[$i];
//            //If the loop is running, we're still under the
//            //threshold, so add them to the list.
//            $inRunoff[] = $c;
//            $shareTotal += $c->getShareOfVotesCast();
//            $i += 1;
//        }

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

    }

    public function constructRunoffList()
    {
        //check if top vote getter is clear winner; stop if they are
        if ($this->isWinner($this->results[0])) return true;
        $i = 0;
        foreach ($this->results as $candidate) {
            //If we got here, we haven't reached a majority yet
            $this->inRunoff->push($candidate);

            if ($this->isRunoffShareAMajority()) {
                //we now need to check if subsequent candidates also have the
                //same vote total.
                while (++$i) {
                    //start with the next candidate
                    $nextCandidate = $this->results[$i];
                    if ($nextCandidate->totalVotesReceived === $candidate->totalVotesReceived) {
                        $this->inRunoff->push($nextCandidate);
                    } else {
                        break 2;
                    }
                }
            }
            $i+=1;
        }

    }

    /**
     * Returns true if share of the votes from each
     * candidate in the runoff list sums to greater than 0.5
     */
    public function isRunoffShareAMajority()
    {
        return $this->inRunoff->pluck('shareOfVotesCast')->sum() > 0.5;
    }

}
