<?php


namespace App\Repositories\Election\Calculators;


use App\Models\Election\Candidate;
use App\Models\Motion;

class PluralityWinnersCalculator
{


    public function __construct(Motion $motion){
        parent::__construct($motion);
    }

    public function getWinners(){}



    public function isWinner(Candidate $candidate){}



}
