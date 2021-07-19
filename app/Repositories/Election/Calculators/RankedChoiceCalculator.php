<?php


namespace App\Repositories\Election\Calculators;


use App\Models\Election\Candidate;
use App\Models\Motion;

class RankedChoiceCalculator extends IResultsCalculator
{


    public function __construct(Motion $motion){
        parent::__construct($motion);
    }

    public function getWinners(Motion $motion){}


    public function isWinner(Candidate $candidate){}

}
