<?php


namespace App\Repositories\Election\Calculators;


use App\Models\Motion;
use App\Repositories\Election\Calculators\MajorityWinnerCalculator;

class ResultsCalculatorFactory
{


    static public function make(Motion $motion){
        if($motion->type === 'proposition') return new PropositionCalculator($motion);

        if($motion->max_winners === 1) return new MajorityWinnerCalculator($motion);

        if($motion->max_winners > 1) return new MultipleWinnersCalculator($motion);




    }





}
