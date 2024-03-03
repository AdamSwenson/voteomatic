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


    public function __construct(Motion $motion)
    {
        //This creates the list of candidates in descending order stored
        //in results
        parent::__construct($motion);

        $this->calculate();
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
        $a = $candidate->getShareOfVotesCast();
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

        return $this->inRunoff->pluck('id')->contains($candidate->id);
    }

    /**
     * Populates winners and inRunoff arrays
     *
     * @return bool|void
     */
    protected function calculate()
    {
        //check if top vote getter is clear winner; stop if they are
        if ($this->isWinner($this->results[0])) return true;

        $i = 0;
        foreach ($this->results as $candidate) {
            //If we got here, we haven't reached a majority yet so we know
            //that the top vote-getter is in a runoff. Thus results[0] gets pushed in
            //on the first run. We continue to push in additional candidates until the loop ends
            $this->inRunoff->push($candidate);

            //Check whether the candidates in the runoff list comprise a majority
            if ($this->isRunoffShareAMajority()) {
                //we now need to check if subsequent candidates also have the
                //same vote total.
                while (++$i) {
                    //start with the next candidate
                    //dev VOT-258 The problem is here
                    // problem is that when all the non winners get added to the run off
                    // there's nothing to stop the iteration. We need a check to stop it
                    // when get bigger than number of results
                    if($i >= sizeof($this->results)) break;
                    $nextCandidate = $this->results[$i];
                    if ($nextCandidate->totalVotesReceived === $candidate->totalVotesReceived) {
                        $this->inRunoff->push($nextCandidate);
                    } else {
                        //break out of both loops
                        break 2;
                    }
                }
            }
            $i+=1;
        }

    }

    /**
     * Returns true if share of the votes from each
     * candidate that is currently in the runoff list
     * sums to greater than 0.5
     */
    public function isRunoffShareAMajority()
    {
        return $this->inRunoff->pluck('shareOfVotesCast')->sum() > 0.5;
    }

}
