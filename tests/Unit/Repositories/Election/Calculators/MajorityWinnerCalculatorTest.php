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

    public $motion;
    /**
     * @var int
     */
    public $winningVotes;
    public \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model $otherCandidates;
    public \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model $candidate;
    public int $numOthers;

    public function setUp(): void
    {
        parent::setUp();

        $this->motion = Motion::factory()->electedOfficeSingleChoice()->create();

        $this->candidate = Candidate::factory()->create(['motion_id' => $this->motion->id]);

        $this->numOthers = 5;
        $this->otherCandidates = Candidate::factory()->count($this->numOthers)->create(['motion_id' => $this->motion->id]);
//$this->otherCandidates = collect($this->otherCandidates)->shuffle();
        $this->winningVotes = 51;
//        $this->winningVotes = $this->faker->numberBetween(10, 100);

    }

    /**
     * Setup utility which gives $this->candidate more than
     * 50% of votes cast. This may not be used in every test
     */
    public function giveCandidateMajorityVotes()
    {
        Vote::factory()->count($this->winningVotes)
            ->create(['motion_id' => $this->motion->id,
                'candidate_id' => $this->candidate->id
            ]);
    }

    public function giveOtherCandidatesVotes($numVotes=10){
        //mix them up in assigning votes to hopefully expose the key order problem
        foreach (collect($this->otherCandidates)->shuffle() as $candidate) {
            Vote::factory()->count($numVotes)
                ->create(['motion_id' => $this->motion->id,
                    'candidate_id' => $candidate->id
                ]);

        }
    }

    /** @test */
    public function checkInstantiation()
    {
        $this->giveCandidateMajorityVotes();
        $this->giveOtherCandidatesVotes();
        //Finally we initialize (have to do this last since things will be loaded on instantiation)
        $this->object = new MajorityWinnerCalculator($this->motion);

        $this->assertTrue($this->motion->is($this->object->motion), "Motion set on calculator object");
        $this->assertEquals($this->numOthers + 1, sizeof($this->object->results), "Expected number of results loaded on calculator object");

    }


    // ================================== isWinner

    /** @test */
    public function isWinnerWhenWon()
    {
        $this->giveCandidateMajorityVotes();
        $this->giveOtherCandidatesVotes();
        //Finally we initialize (have to do this last since things will be loaded on instantiation)
        $this->object = new MajorityWinnerCalculator($this->motion);

        $this->assertTrue($this->object->isWinner($this->candidate), "Correctly identifies winner");
    }


    /** @test */
    public function isWinnerWhenLost()
    {
        $this->giveCandidateMajorityVotes();
        $this->giveOtherCandidatesVotes();
        //Finally we initialize (have to do this last since things will be loaded on instantiation)
        $this->object = new MajorityWinnerCalculator($this->motion);

        foreach ($this->otherCandidates as $candidate) {
            $this->assertFalse($this->object->isWinner($candidate), "Correctly identifies loser");
        }
    }


    /** @test */
    public function isWinnerWhenTied()
    {
        $this->giveCandidateMajorityVotes();
        $this->giveOtherCandidatesVotes();
        $e = Candidate::factory()->create(['motion_id' => $this->motion->id]);
        Vote::factory()->count($this->winningVotes)->create(['motion_id' => $this->motion->id,
            'candidate_id' =>  $e->id
        ]);

        //Finally we initialize (have to do this last since things will be loaded on instantiation)
        $this->object = new MajorityWinnerCalculator($this->motion);

        //check
        $this->assertFalse($this->object->isWinner($this->candidate), "Correctly returns false for candidate 1 when tied");
        $this->assertFalse($this->object->isWinner($e), "Correctly returns false for candidate 2 when tied");
    }

    /** @test */
    public function isWinnerWhenPlurality()
    {
        //Since this is used for elections where the winner must secure a majority,
        //we validate that it doesn't declare a winner when someone has the most votes
        $this->giveCandidateMajorityVotes();
        $this->giveOtherCandidatesVotes();
        $e = Candidate::factory()->create(['motion_id' => $this->motion->id]);
        //With the other candidates in the race, adding the runner up with 2 less votes makes the main candidate
        //the plurality but not majority winner
        Vote::factory()->count($this->winningVotes -2 )->create(['motion_id' => $this->motion->id,
            'candidate_id' =>  $e->id
        ]);

        $this->assertLessThan(0.5, $this->candidate->getShareOfVotesCast(), "Set up is valid: candidate does not have a majority");

        //Finally we initialize (have to do this last since things will be loaded on instantiation)
        $this->object = new MajorityWinnerCalculator($this->motion);

        //check
        $this->assertFalse($this->object->isWinner($this->candidate), "Correctly returns false when plurality but not majority winner");
    }


    // ================================== isRunoffParticipant

    /** @test */
    public function isRunoffParticipantWhenWinner(){
        $this->giveCandidateMajorityVotes();
        $this->giveOtherCandidatesVotes();
        //Finally we initialize (have to do this last since things will be loaded on instantiation)
        $this->object = new MajorityWinnerCalculator($this->motion);
        $this->assertTrue($this->object->isWinner($this->candidate), "setup is valid");

        //check
        $this->assertFalse($this->object->isRunoffParticipant($this->candidate), "isRunoffParticipant returns false when winner");
    }

    /** @test */
    public function isRunoffParticipantWhenTied(){
        $this->giveCandidateMajorityVotes();
        $this->giveOtherCandidatesVotes();
        $e = Candidate::factory()->create(['motion_id' => $this->motion->id]);
        Vote::factory()->count($this->winningVotes)
            ->create([
                'motion_id' => $this->motion->id,
                'candidate_id' =>  $e->id
            ]);
        $this->motion->save();

        //Finally we initialize (have to do this last since things will be loaded on instantiation)
        $this->object = new MajorityWinnerCalculator($this->motion);
        $this->assertEquals($this->numOthers +2, sizeof($this->object->results), "setup validation: Correct count of candidates in object");

        //check
        $this->assertTrue($this->object->isRunoffParticipant($this->candidate), "Correctly returns true for candidate 1 when have same score");
        $this->assertTrue($this->object->isRunoffParticipant($e), "Correctly returns true for candidate 2 when have same score");
        foreach ($this->otherCandidates as $candidate) {
            $this->assertFalse($this->object->isRunoffParticipant($candidate), "Other candidates are not in runoff when there is a tie for first");
        }
    }

    /** @test */
    public function isRunoffParticipantWhenPlurality(){
        $this->giveCandidateMajorityVotes();
        $this->giveOtherCandidatesVotes();
        $e = Candidate::factory()->create(['motion_id' => $this->motion->id]);
        //With the other candidates in the race, adding the runner up with 2 less votes makes the main candidate
        //the plurality but not majority winner
        Vote::factory()->count($this->winningVotes -2 )->create(['motion_id' => $this->motion->id,
            'candidate_id' =>  $e->id
        ]);
        $this->assertLessThan(0.5, $this->candidate->getShareOfVotesCast(), "Set up validation: candidate does not have a majority");

        //Finally we initialize (have to do this last since things will be loaded on instantiation)
        $this->object = new MajorityWinnerCalculator($this->motion);
        $this->assertEquals($this->numOthers +2, sizeof($this->object->results), "setup validation: Correct count of candidates in object");

        //check
        $this->assertTrue($this->object->isRunoffParticipant($this->candidate), "Correctly returns true when have same score");

    }

    /** @test */
    public function isRunoffParticipantWhenTieAtTopInPlurality(){
        //prep
        $totals = [ 40, 40, 15, 5];
        $expected = [true, true, false, false];

        $candidates = [$this->candidate];
        foreach($this->otherCandidates as $candidate){
            $candidates[] = $candidate;
        }
        shuffle($candidates);
        for($i=0;$i<sizeof($totals); $i++) {
            Vote::factory()->count($totals[$i])
                ->create([
                    'motion_id' => $this->motion->id,
                    'candidate_id' => $candidates[$i]->id
                ]);
        }

        //call(ish)
        $this->object = new MajorityWinnerCalculator($this->motion);
        $this->assertEquals(sizeof($candidates), sizeof($this->object->results), "setup validation: Correct count of candidates in object");

        //check
        for($i=0;$i<sizeof($totals); $i++) {
            $this->assertEquals($expected[$i], $this->object->isRunoffParticipant($candidates[$i]), "isRunoffParticipant returns expected value");
        }

    }

    /** @test */
    public function isRunoffParticipantWhenMultipleTiesAtTopInPlurality(){
        //prep
        $totals = [ 20, 20, 20, 20, 15, 5];
        $expected = [true, true, true, true, false, false];

        $candidates = [$this->candidate];
        foreach($this->otherCandidates as $candidate){
            $candidates[] = $candidate;
        }
        shuffle($candidates);
        for($i=0;$i<sizeof($totals); $i++) {
            Vote::factory()->count($totals[$i])
                ->create([
                    'motion_id' => $this->motion->id,
                    'candidate_id' => $candidates[$i]->id
                ]);
        }

        //call(ish)
        $this->object = new MajorityWinnerCalculator($this->motion);
        $this->assertEquals(sizeof($totals), sizeof($this->object->results), "setup validation: Correct count of candidates in object");

        //check
        for($i=0;$i<sizeof($totals); $i++) {
            $this->assertEquals($expected[$i], $this->object->isRunoffParticipant($candidates[$i]), "isRunoffParticipant returns expected value");
        }

    }


    /** @test */
    public function isRunoffParticipantWhenTiesBelowTopInPlurality(){
        //prep
        $totals = [ 40, 25, 25, 10];
        $expected = [true, true, true, false];

        $candidates = [$this->candidate];
        foreach($this->otherCandidates as $candidate){
            $candidates[] = $candidate;
        }
        shuffle($candidates);
        for($i=0;$i<sizeof($totals); $i++) {
            Vote::factory()->count($totals[$i])
                ->create([
                    'motion_id' => $this->motion->id,
                    'candidate_id' => $candidates[$i]->id
                ]);
        }

        //call(ish)
        $this->object = new MajorityWinnerCalculator($this->motion);
//        $this->assertEquals(sizeof($totals), sizeof($this->object->results), "setup validation: Correct count of candidates in object");

        //check
        for($i=0;$i<sizeof($totals); $i++) {
            $this->assertEquals($expected[$i], $this->object->isRunoffParticipant($candidates[$i]), "isRunoffParticipant returns expected value");
        }

    }

}
