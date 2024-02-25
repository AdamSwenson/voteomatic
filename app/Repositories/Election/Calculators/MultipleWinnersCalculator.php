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

    public int $maxWinners;

    public function __construct(Motion $motion)
    {
        $this->maxWinners = $motion->max_winners;

        //Loads candidate objects and puts them in results array in descending order of vote total
        parent::__construct($motion);

        $this->calculate();
    }


    /**
     * Returns true if there's room.
     * Do not pass in a collection containing only 1
     * @param $possibleAddition array | Candidate
     * @return bool
     */
    public function isRoomInWinners($possibleAddition)
    {
        if ($possibleAddition instanceof Candidate) {
            $possibleAddition = [$possibleAddition];
        }
        $winners = sizeof($this->winners);
        $add = sizeof($possibleAddition);
        return ($winners + $add) <= $this->maxWinners;
    }

    /**
     * Populates winners and inRunoff arrays
     * @return void
     */
    protected function calculate()
    {
        /*
         * Cases
         * (1) No ties at all.
         * (2) Tie(s) within winner slots; not among non-winners
         * (3) No tie(s) within winners; tie(s) within non-winners
         * (4) Tie(s) within winner slots; tie(s) within non-winners
         */

        $k = 0;
        //Using a while loop because need to control how much the
        // index is increased on each run.
        while ($k < sizeof($this->results)) {

            $candidate = $this->results[$k];

            //check if they are tied with anyone.
            //This will return an array with at least one candidate (viz, the
            //one we provided
            $toAdd = $this->getTies($candidate);

            if ( ! $this->isRoomInWinners($toAdd) ) { //checks whether all the slots are full

                //todo Looks like the problem is that this does not check whether we've exceeded max winner count. It only checks if there's a tie

                if (sizeof($toAdd) > 1) {
                    //If there is no room, we need a runoff if there's more than one
                    //person with that vote total
                    $this->inRunoff = $this->inRunoff->concat($toAdd);
                }
                //either way, we've reached the end of the winners
                break;
            }

            //There is room, so they are winner(s)
            $this->winners = $this->winners->concat($toAdd);

            //dev
            if(sizeof($this->winners) === $this->maxWinners){
                //if we've fully filled the winners array, we can stop
                break;
            }

            //Increment by number just added. This ensures that we jump past all members
            //of a tied rank and don't duplicate
            //If we've reached the end, we will find out on the next loop
            //so we increment by the number of items we just added.
            $k += sizeof($toAdd);
        }

    }

    /**
     * Determine whether the candidate is an outright winner
     * @param Candidate $candidate
     * @return bool
     */
    public function isWinner(Candidate $candidate)
    {
        return $this->winners->pluck('id')->contains($candidate->id);
    }

    /**
     * Determine whether the candidate must participate in a runoff
     * @param Candidate $candidate
     * @return bool
     */
    public function isRunoffParticipant(Candidate $candidate)
    {
        return $this->inRunoff->pluck('id')->contains($candidate->id);
    }


}
