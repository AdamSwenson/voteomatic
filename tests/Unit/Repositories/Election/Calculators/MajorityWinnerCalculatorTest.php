<?php

namespace Tests\Repositories\Election\Calculators;

use App\Models\Election\Candidate;
use App\Models\Motion;
use App\Models\Vote;
use App\Repositories\Election\Calculators\MajorityWinnerCalculator;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class MajorityWinnerCalculatorTest extends TestCase
{

    private $motion;
    /**
     * @var int
     */
    private $winningVotes;

    public function setUp(): void
    {
        parent::setUp();

        $this->motion = Motion::factory()->electedOfficeSingleChoice()->create();


        $this->candidate = Candidate::factory()->create(['motion_id' => $this->motion->id]);

        $this->numOthers = 5;
        $this->otherCandidates = Candidate::factory()->count($this->numOthers)->create(['motion_id' => $this->motion->id]);

        $this->winningVotes = 51;
//        $this->winningVotes = $this->faker->numberBetween(10, 100);

        Vote::factory()->count($this->winningVotes)
            ->create(['motion_id' => $this->motion->id,
                'candidate_id' => $this->candidate->id
            ]);

        foreach ($this->otherCandidates as $candidate) {

            Vote::factory()->count(10)
                ->create(['motion_id' => $this->motion->id,
                    'candidate_id' => $candidate->id
                ]);

        }


    }

    /** @test */
    public function checkInstantiation()
    {
        //Finally we initialize (have to do this last since things will be loaded on instantiation)
        $this->object = new MajorityWinnerCalculator($this->motion);

        $this->assertTrue($this->motion->is($this->object->motion), "Motion set on calculator object");
        $this->assertEquals($this->numOthers + 1, sizeof($this->object->results), "Expected number of results loaded on calculator object");

    }

    /** @test */
    public function isWinnerWhenWon()
    {
        //Finally we initialize (have to do this last since things will be loaded on instantiation)
        $this->object = new MajorityWinnerCalculator($this->motion);

        $this->assertTrue($this->object->isWinner($this->candidate), "Correctly identifies winner");

    }


    /** @test */
    public function isWinnerWhenLost()
    {
        //Finally we initialize (have to do this last since things will be loaded on instantiation)
        $this->object = new MajorityWinnerCalculator($this->motion);

        foreach ($this->otherCandidates as $candidate) {

            $this->assertFalse($this->object->isWinner($candidate), "Correctly identifies loser");
        }
    }


    /** @test */
    public function isWinnerWhenTied()
    {
        $e = Candidate::factory()->create(['motion_id' => $this->motion->id]);
        Vote::factory()->count($this->winningVotes)->create(['motion_id' => $this->motion->id,
            'candidate_id' =>  $e->id
        ]);

        $s = $e->getShareOfVotesCast();

        //Finally we initialize (have to do this last since things will be loaded on instantiation)
        $this->object = new MajorityWinnerCalculator($this->motion);


        //check
        $this->assertFalse($this->object->isWinner($this->candidate), "Correctly returns false when tied");

    }

    /** @test */
    public function isRunoffParticipant(){
        $e = Candidate::factory()->create(['motion_id' => $this->motion->id]);
        Vote::factory()->count($this->winningVotes)
            ->create([
                'motion_id' => $this->motion->id,
            'candidate_id' =>  $e->id
        ]);
        $this->motion->save();

        //Finally we initialize (have to do this last since things will be loaded on instantiation)
        $this->object = new MajorityWinnerCalculator($this->motion);


        //check
        $this->assertEquals(7, sizeof($this->object->results), "Correct count of candidates in object");

        $this->assertTrue($this->object->isRunoffParticipant($this->candidate), "Correctly returns true when have same score");

    }
}
