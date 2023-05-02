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

        $this->numOthers = 5;
        $this->otherCandidates = Candidate::factory()->count($this->numOthers)->create(['motion_id' => $this->motion->id]);

    }

    // ============================= Utilities
    /** @test */
    public function getTies()
    {
        //prep
        $totals = [40, 20, 20, 20];

        $candidates = [];
        foreach ($this->otherCandidates as $candidate) {
            $candidates[] = $candidate;
        }
        for ($i = 0; $i < sizeof($totals); $i++) {
            Vote::factory()->count($totals[$i])
                ->create([
                    'motion_id' => $this->motion->id,
                    'candidate_id' => $candidates[$i]->id
                ]);
        }

        $this->object = new MultipleWinnersCalculator($this->motion);

        //call
        $idx = 1;
        $result = $this->object->getTies($candidates[$idx]);

        //check
        $this->assertEquals(3, sizeof($result), "Returns expected size (all ties including person checked)");
        foreach ($result as $r) {
            $this->assertEquals($totals[$idx], $r->totalVotesReceived, "Returned candidate has expected vote total");
        }
    }

    /** @test */
    public function getTiesWhenNotTied()
    {
        //prep
        $totals = [40, 20, 20, 20];

        $candidates = [];
        foreach ($this->otherCandidates as $candidate) {
            $candidates[] = $candidate;
        }
        for ($i = 0; $i < sizeof($totals); $i++) {
            Vote::factory()->count($totals[$i])
                ->create([
                    'motion_id' => $this->motion->id,
                    'candidate_id' => $candidates[$i]->id
                ]);
        }

        $this->object = new MultipleWinnersCalculator($this->motion);

        //call
        $idx = 0;
        $result = $this->object->getTies($candidates[$idx]);

        //check
        $this->assertEquals(1, sizeof($result), "Returns expected size (all ties including person checked)");
        foreach ($result as $r) {
            $this->assertEquals($totals[$idx], $r->totalVotesReceived, "Returned candidate has expected vote total");
        }


    }


    /** @test  */
    public function isRoomInWinners()
    {
        $r = $this->otherCandidates[0];
        $this->assertInstanceOf(Candidate::class, $r, 'setup validation');
        $this->object = new MultipleWinnersCalculator($this->motion);
        //overwrite what was added in setup/instantiation
        $this->object->maxWinners = 4;
        $this->object->winners = collect(Candidate::factory()->count(3)->make());

        $this->assertTrue($this->object->isRoomInWinners($r), "returns true when room and given object");
//        $this->assertTrue($this->object->isRoomInWinners(collect($r)), "returns true when room and given collection");
        $this->assertTrue($this->object->isRoomInWinners([$r]), "returns true when room and given array");

    }

    /** @test */
    public function isRoomInWinnersForMultiple()
    {
        $r = collect(Candidate::factory()->count(2)->make());

        $this->object = new MultipleWinnersCalculator($this->motion);
        //overwrite what was added in setup/instantiation
        $this->object->maxWinners = 4;
        $this->object->winners = collect(Candidate::factory()->count(2)->make());

        $this->assertTrue($this->object->isRoomInWinners(collect($r)), "returns true when room and given collection");
    }

    /** @test  */
    public function isRoomInWinnersWhenNotForSingle()
    {
        $r = $this->otherCandidates[0];
        $this->assertInstanceOf(Candidate::class, $r, 'setup validation');
        $this->object = new MultipleWinnersCalculator($this->motion);
        //overwrite what was added in setup/instantiation
        $this->object->maxWinners = 4;
        $this->object->winners = collect(Candidate::factory()->count(4)->make());

        $this->assertFalse($this->object->isRoomInWinners($r), "returns false when no room and given object");
        $this->assertFalse($this->object->isRoomInWinners([$r]), "returns false when no room and given array");
    }


// =============================== isWinner

    /** @test */
    public function isWinnerWhenWon()
    {
        //prep
        $totals = [40, 20, 20, 20];

        $candidates = [];
        foreach ($this->otherCandidates as $candidate) {
            $candidates[] = $candidate;
        }
        for ($i = 0; $i < sizeof($totals); $i++) {
            Vote::factory()->count($totals[$i])
                ->create([
                    'motion_id' => $this->motion->id,
                    'candidate_id' => $candidates[$i]->id
                ]);
        }

        $this->object = new MultipleWinnersCalculator($this->motion);

        //check
        $this->assertTrue($this->object->isWinner($candidates[0]), "Correctly identifies winner");
    }


    /** @test */
    public function isWinnerWhenLost()
    {
        //prep
        $totals = [40, 20, 20, 15, 5];

        $candidates = [];
        foreach ($this->otherCandidates as $candidate) {
            $candidates[] = $candidate;
        }
        for ($i = 0; $i < sizeof($totals); $i++) {
            Vote::factory()->count($totals[$i])
                ->create([
                    'motion_id' => $this->motion->id,
                    'candidate_id' => $candidates[$i]->id
                ]);
        }

        $this->object = new MultipleWinnersCalculator($this->motion);

        //check
        $this->assertFalse($this->object->isWinner($candidates[4]), "Correctly identifies loser");
    }


    /** @test */
    public function isWinnerWhenTiedAtTop()
    {
        //prep
        $totals = [40, 40, 15, 5];
        $expected = [true, true, true, false];

        $candidates = [];
        foreach ($this->otherCandidates as $candidate) {
            $candidates[] = $candidate;
        }
        for ($i = 0; $i < sizeof($totals); $i++) {
            Vote::factory()->count($totals[$i])
                ->create([
                    'motion_id' => $this->motion->id,
                    'candidate_id' => $candidates[$i]->id
                ]);
        }

        $this->object = new MultipleWinnersCalculator($this->motion);

        //check
        for ($i = 0; $i < sizeof($totals); $i++) {
            $this->assertEquals($expected[$i], $this->object->isWinner($candidates[$i]), "isRunoffParticipant returns expected value");
        }
    }

    /** @test */
    public function isWinnerWhenTieAtTopRequiresRunoff()
    {
        //prep
        $totals = [25, 25, 25, 25, 0];
        $expected = [false, false, false, false, false];

        $candidates = [];
        foreach ($this->otherCandidates as $candidate) {
            $candidates[] = $candidate;
        }
        for ($i = 0; $i < sizeof($totals); $i++) {
            Vote::factory()->count($totals[$i])
                ->create([
                    'motion_id' => $this->motion->id,
                    'candidate_id' => $candidates[$i]->id
                ]);
        }

        $this->object = new MultipleWinnersCalculator($this->motion);

        //check
        for ($i = 0; $i < sizeof($totals); $i++) {
            $this->assertEquals($expected[$i], $this->object->isWinner($candidates[$i]), "isRunoffParticipant returns expected value");
        }
    }

    /** @test */
    public function isWinnerWhenTiedBelowTop()
    {
        //prep
        $totals = [40, 20, 20, 5];
        $expected = [true, true, true, false];

        $candidates = [];
        foreach ($this->otherCandidates as $candidate) {
            $candidates[] = $candidate;
        }
        for ($i = 0; $i < sizeof($totals); $i++) {
            Vote::factory()->count($totals[$i])
                ->create([
                    'motion_id' => $this->motion->id,
                    'candidate_id' => $candidates[$i]->id
                ]);
        }

        $this->object = new MultipleWinnersCalculator($this->motion);

        //check
        for ($i = 0; $i < sizeof($totals); $i++) {
            $this->assertEquals($expected[$i], $this->object->isWinner($candidates[$i]), "isRunoffParticipant returns expected value");
        }
    }

    /** @test */
    public function isWinnerWhenTieBelowTopRequiresRunoff()
    {
        //prep
        $totals = [40, 20, 20, 20];
        $expected = [true, false, false, false];

        $candidates = [];
        foreach ($this->otherCandidates as $candidate) {
            $candidates[] = $candidate;
        }
        for ($i = 0; $i < sizeof($totals); $i++) {
            Vote::factory()->count($totals[$i])
                ->create([
                    'motion_id' => $this->motion->id,
                    'candidate_id' => $candidates[$i]->id
                ]);
        }

        $this->object = new MultipleWinnersCalculator($this->motion);

        //check
        for ($i = 0; $i < sizeof($totals); $i++) {
            $this->assertEquals($expected[$i], $this->object->isWinner($candidates[$i]), "isRunoffParticipant returns expected value");
        }
    }


    // ================================= isRunoffParticipant


    /** @test */
    public function isRunoffParticipantWhenTiedAtTop()
    {
        //prep
        $totals = [40, 40, 15, 5];
        $expected = [false, false, false, false];

        $candidates = [];
        foreach ($this->otherCandidates as $candidate) {
            $candidates[] = $candidate;
        }
        for ($i = 0; $i < sizeof($totals); $i++) {
            Vote::factory()->count($totals[$i])
                ->create([
                    'motion_id' => $this->motion->id,
                    'candidate_id' => $candidates[$i]->id
                ]);
        }

        $this->object = new MultipleWinnersCalculator($this->motion);

        //check
        for ($i = 0; $i < sizeof($totals); $i++) {
            $this->assertEquals($expected[$i], $this->object->isRunoffParticipant($candidates[$i]), "isRunoffParticipant returns expected value");
        }
    }

    /** @test */
    public function isRunoffParticipantWhenTieAtTopRequiresRunoff()
    {
        //prep
        $totals = [25, 25, 25, 25, 0];
        $expected = [true, true, true, true, false];

        $candidates = [];
        foreach ($this->otherCandidates as $candidate) {
            $candidates[] = $candidate;
        }
        for ($i = 0; $i < sizeof($totals); $i++) {
            Vote::factory()->count($totals[$i])
                ->create([
                    'motion_id' => $this->motion->id,
                    'candidate_id' => $candidates[$i]->id
                ]);
        }

        $this->object = new MultipleWinnersCalculator($this->motion);

        //check
        for ($i = 0; $i < sizeof($totals); $i++) {
            $this->assertEquals($expected[$i], $this->object->isRunoffParticipant($candidates[$i]), "isRunoffParticipant returns expected value");
        }
    }

    /** @test */
    public function isRunoffParticipantWhenTiedBelowTop()
    {
        //prep
        $totals = [40, 20, 20, 5];
        $expected = [false, false, false, false];

        $candidates = [];
        foreach ($this->otherCandidates as $candidate) {
            $candidates[] = $candidate;
        }
        for ($i = 0; $i < sizeof($totals); $i++) {
            Vote::factory()->count($totals[$i])
                ->create([
                    'motion_id' => $this->motion->id,
                    'candidate_id' => $candidates[$i]->id
                ]);
        }

        $this->object = new MultipleWinnersCalculator($this->motion);

        //check
        for ($i = 0; $i < sizeof($totals); $i++) {
            $this->assertEquals($expected[$i], $this->object->isRunoffParticipant($candidates[$i]), "isRunoffParticipant returns expected value");
        }
    }

    /** @test */
    public function isRunoffParticipantWhenTieBelowTopRequiresRunoff()
    {
        //prep
        $totals = [40, 20, 20, 20];
        $expected = [false, true, true, true];

        $candidates = [];
        foreach ($this->otherCandidates as $candidate) {
            $candidates[] = $candidate;
        }
        for ($i = 0; $i < sizeof($totals); $i++) {
            Vote::factory()->count($totals[$i])
                ->create([
                    'motion_id' => $this->motion->id,
                    'candidate_id' => $candidates[$i]->id
                ]);
        }

        $this->object = new MultipleWinnersCalculator($this->motion);

        //check
        for ($i = 0; $i < sizeof($totals); $i++) {
            $this->assertEquals($expected[$i], $this->object->isRunoffParticipant($candidates[$i]), "isRunoffParticipant returns expected value");
        }
    }


    /**
     * @test
     * Addresses VOT-254. That bug was marking all ties as runoff participants even
     * when there were winners
     */
    public function vot254_isRunoffParticipantTrueWhenTieBelowTopDoesNotRequireRunoff()
    {
        //prep
        //Going to do clean setup with actual 2023 EEC results
        $this->max_winners = 3;
        //Actual 2023 EEC results
        $totals = [19, 17, 15, 9, 9, 8 ];
        $expectedIsRunoffParticipant = [false, false, false, false, false, false];

        $this->motion = Motion::factory()->electedOffice()->create(['max_winners' => $this->maxWinners]);

        $candidates = Candidate::factory()->count(sizeof($totals))->create(['motion_id' => $this->motion->id]);
        $this->assertEquals(sizeof($candidates), sizeof($totals), "checking test setup: candidates");
        $this->assertEquals(sizeof($expectedIsRunoffParticipant), sizeof($totals), "checking test setup: runoff participants");

        for ($i = 0; $i < sizeof($totals); $i++) {
            Vote::factory()->count($totals[$i])
                ->create([
                    'motion_id' => $this->motion->id,
                    'candidate_id' => $candidates[$i]->id
                ]);
        }

        $this->object = new MultipleWinnersCalculator($this->motion);

        //check
        $this->assertEquals($this->max_winners, sizeof($this->object->winners), "Correct number of winners in calculator's winners array");
        $i = 0;
        foreach($candidates as $candidate){
            if($i < $this->max_winners){
                $this->assertTrue($this->object->isWinner($candidate), "isWinner correctly identifies winners on candidate #" . $i);
            }else{
                $this->assertFalse($this->object->isWinner($candidate), "isWinner correctly identifies non-winners on candidate #" . $i);
            }

            $b = $this->object->isRunoffParticipant($candidate);
            $this->assertEquals($expectedIsRunoffParticipant[$i], $this->object->isRunoffParticipant($candidate), "isRunoffParticipant returns expected value");

            $i += 1;
        }

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
//    public function createVotes(): void
//    {
//        Vote::factory()->count($this->winningVotes)
//            ->create(['motion_id' => $this->motion->id,
//                'candidate_id' => $this->winners[0]->id
//            ]);
//
////the second and third are tied
//        Vote::factory()->count($this->winningVotes - 1)
//            ->create(['motion_id' => $this->motion->id,
//                'candidate_id' => $this->winners[1]->id
//            ]);
//
//        Vote::factory()->count($this->winningVotes - 1)
//            ->create(['motion_id' => $this->motion->id,
//                'candidate_id' => $this->winners[2]->id
//            ]);
//
////losers
//        foreach ($this->otherCandidates as $candidate) {
//
//            Vote::factory()->count(10)
//                ->create(['motion_id' => $this->motion->id,
//                    'candidate_id' => $candidate->id
//                ]);
//
//        }
//    }
}
