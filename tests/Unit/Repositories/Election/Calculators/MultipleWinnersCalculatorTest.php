<?php

namespace Tests\Repositories\Election\Calculators;

use App\Models\Election\Candidate;
use App\Models\Motion;
use App\Models\Vote;
use App\Repositories\Election\Calculators\MajorityWinnerCalculator;
use App\Repositories\Election\Calculators\MultipleWinnersCalculator;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class MultipleWinnersCalculatorTest extends TestCase
{

    public $motion;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public $candidate;
    /**
     * @var int
     */
    public $numOthers;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public $otherCandidates;
    /**
     * @var int
     */
    public $winningVotes;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public $winners;

    public function setUp(): void
    {
        parent::setUp();

        $this->maxWinners = 3;
        $this->motion = Motion::factory()->electedOffice()->create(['max_winners' => $this->maxWinners]);

        $this->winners = Candidate::factory()->count($this->maxWinners)->create(['motion_id' => $this->motion->id]);

        $this->numOthers = 5;
        $this->otherCandidates = Candidate::factory()->count($this->numOthers)->create(['motion_id' => $this->motion->id]);

        $this->winningVotes = 51;
//        $this->winningVotes = $this->faker->numberBetween(10, 100);

        Vote::factory()->count($this->winningVotes)
            ->create(['motion_id' => $this->motion->id,
                'candidate_id' => $this->winners[0]->id
            ]);

//the second and third are tied
        Vote::factory()->count($this->winningVotes - 1)
            ->create(['motion_id' => $this->motion->id,
                'candidate_id' => $this->winners[1]->id
            ]);

        Vote::factory()->count($this->winningVotes - 1)
            ->create(['motion_id' => $this->motion->id,
                'candidate_id' => $this->winners[2]->id
            ]);

//losers
        foreach ($this->otherCandidates as $candidate) {

            Vote::factory()->count(10)
                ->create(['motion_id' => $this->motion->id,
                    'candidate_id' => $candidate->id
                ]);

        }


    }


    /** @test */
    public function isWinnerWhenWon()
    {
        $this->object = new MultipleWinnersCalculator($this->motion);

//        foreach ($this->winners as $winner) {
            $this->assertTrue($this->object->isWinner($this->winners[0]), "Correctly identifies winner");
//
//        }

    }


    /** @test */
    public function isWinnerWhenLost()
    {
        //Finally we initialize (have to do this last since things will be loaded on instantiation)
        $this->object = new MultipleWinnersCalculator($this->motion);


        foreach ($this->otherCandidates as $candidate) {

            $this->assertFalse($this->object->isWinner($candidate), "Correctly identifies loser");
        }
    }


    /** @test */
    public function isWinnerWhenTied()
    {
//        $this->object = new MultipleWinnersCalculator($this->motion);

//        $e = Candidate::factory()->create(['motion_id' => $this->motion->id]);
//        Vote::factory()->count($this->winningVotes)->create(['motion_id' => $this->motion->id,
//            'candidate_id' =>  $e->id
//        ]);
//
//        $s = $e->getShareOfVotesCast();
//
//        //Finally we initialize (have to do this last since things will be loaded on instantiation)
//        $this->object = new MajorityWinnerCalculator($this->motion);
//
//
//        //check
//        $this->assertFalse($this->object->isWinner($this->candidate), "Correctly returns false when tied");

    }

//    /** @test */
//    public function isRunoffParticipant(){
//$this->object = new MultipleWinnersCalculator($this->motion);

//        $e = Candidate::factory()->create(['motion_id' => $this->motion->id]);
//        Vote::factory()->count($this->winningVotes)
//            ->create([
//                'motion_id' => $this->motion->id,
//                'candidate_id' =>  $e->id
//            ]);
//        $this->motion->save();
//
//        //Finally we initialize (have to do this last since things will be loaded on instantiation)
//        $this->object = new MajorityWinnerCalculator($this->motion);
//
//
//        //check
//        $this->assertEquals(7, sizeof($this->object->results), "Correct count of candidates in object");
//
//        $this->assertTrue($this->object->isRunoffParticipant($this->candidate), "Correctly returns true when have same score");
//
//    }
}
