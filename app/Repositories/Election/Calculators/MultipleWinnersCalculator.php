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

    public mixed $maxWinners;

    public function __construct(Motion $motion)
    {
        $this->maxWinners = $motion->max_winners;

        //Loads candidate objects and puts them in results array in descending order of vote total
        parent::__construct($motion);

        $this->calculate();
    }


    /**
     * Returns true if there's room. Do not pass a collection of 1
     * @param $possibleAddition
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

    public function calculate()
    {
        $k = 0;
        //Using a while loop because need to control how much the
        // index is increased on each run
        while ($k < sizeof($this->results)) {
            $candidate = $this->results[$k];

            //check if they are tied with anyone.
            //This will return an array with at least one candidate (viz, the
            //one we provided
            $toAdd = $this->getTies($candidate);

            if (!$this->isRoomInWinners($toAdd)) {
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

            //If we've reached the end, we will find out on the next loop
            //so we increment by the number of items we just added.
            $k += sizeof($toAdd);
        }
//
//
//
//                if(sizeof($toAdd) === 0){
//                if($this->isRoomInWinners($candidate)){
//                    $this->winners->push($candidate);
//                }else{
//                    //We will hit this on the first person who doesn't fit
//                    break;
//                }
//            }else{
//
//                if($this->isRoomInWinners($candidate)){
//                    $this->winners->concat($candidate);
//                }else{
//                    //We will hit this on the first person who doesn't fit
//                    break;
//                }
//            }


//            if (sizeof($this->winners) < $this->maxWinners


//        $i = 0;
//            $a = $candidate->totalVotesReceived;
//        foreach ($this->results as $candidate) {
//            $i += 1;
        //We need to start checking when we are at one less than
        //the number of winners, because the person may be in a runoff
//            if (sizeof($this->winners) === $this->maxWinners - 1) {
//                $nextCandidate = $this->results[$k + 1]; //->get($i);
//                if ($candidate->totalVotesReceived > $nextCandidate->totalVotesReceived) {
//                    //if there is no tie, we add them and are done
//                    $this->winners->push($candidate);
//                    break;
//                }
//
////                $c = $candidate->id;
////                $b = $nextCandidate->totalVotesReceived;
////                $d = $nextCandidate->id;
//                //we have a tie and need to begin the runoff check
//                $this->inRunoff->push($candidate);
//                $this->inRunoff->push($nextCandidate);
//                $i = $k + 2;
//                while (++$i) {
//                    if ($i === sizeof($this->results)) break 2; //we've been through all of them
//                    $candidate3 = $this->results[$i]; //->get($i);
//                    if ($nextCandidate->totalVotesRecieved > $candidate3->totalVotesReceived) {
//                        break 2;
//                    }
//                    //that candidate is also tied so we push them into the runoff slot and keep going
//                    $this->inRunoff->push($candidate3);
//                    $nextCandidate = $candidate3;
//                }
//
//            }
//
//            //Otherwise we add them and keep going
//            $this->winners->push($candidate);
//        }

    }


    /**
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

    }


}
