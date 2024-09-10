<?php

namespace Tests\Repositories\Election\Calculators;

use App\Models\Vote;
use App\Repositories\Election\Calculators\MajorityWinnerCalculator;
use App\Repositories\Election\Calculators\PluralityWinnersCalculator;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;


class PluralityWinnersCalculatorTest extends TestCase
{


    /**
     * @test
     * Addresses VOT-254
     */
    public function isRunoffParticipantWhenTieBelowTopDoesNotRequireRunoff()
    {
        $this->markTestIncomplete('needs creation');

//
//        //prep
//
//        $totals = [40, 20, 15, 15, ];
//        $expected = [false, false, false, false];
//
//        $candidates = [];
//        foreach ($this->otherCandidates as $candidate) {
//            $candidates[] = $candidate;
//        }
//        for ($i = 0; $i < sizeof($totals); $i++) {
//            Vote::factory()->count($totals[$i])
//                ->create([
//                    'motion_id' => $this->motion->id,
//                    'candidate_id' => $candidates[$i]->id
//                ]);
//        }
//
//        $this->object = new PluralityWinnersCalculator($this->motion);
//
//        //check
//        for ($i = 0; $i < sizeof($totals); $i++) {
//            $this->assertEquals($expected[$i], $this->object->isRunoffParticipant($candidates[$i]), "isRunoffParticipant returns expected value");
//        }
    }

}
