<?php

namespace App\Unit\Models;
//namespace Tests\Unit;
//namespace Tests;

use Tests\TestCase;

//use PHPUnit\Framework\TestCase;

class MotionTestCase extends TestCase
{

    public $object;
    public $nayVotes;
    public int $totalYay;
    public int $totalNay;
    public $yayVotes;

    public function setUp()
    {
        parent::setUp();


        $this->object = Motion::factory()->create();

        //ensuring at least one is non zero, given the high chance of 0s
        $this->totalYay = $this->faker->randomNumber(1) + 1;
        $this->totalNay = $this->faker->randomNumber(1);

        $this->createVotes();


//        $this->object = factory(Motion::class)->create();

    }

    /**
     * Helper. Abstracted to make it easier to tweak whether the object
     * requires a majority or other count
     */
    public function createVotes()
    {
        $this->yayVotes = Vote::factory(['motion_id' => $this->object->id])->count($this->totalYay)->affirmative()->create();
        $this->nayVotes = Vote::factory(['motion_id' => $this->object->id])->count($this->totalNay)->negative()->create();

        $this->votes = collect();
        $this->votes->merge($this->yayVotes)->merge($this->nayVotes);

//        foreach ($this->votes as $vote) {
//            $this->object->votes()->save($vote);
//        }

//        $this->object->save();
//
//
//        foreach ($this->votes as $vote) {
//
//            $vote->motion()->associate($this->object);
//            $vote->save();
//        }
    }




    // ---------------------- test attributes

    /** @test */
    public function affirmativeVotes()
    {
        $yays = $this->object->affirmativeVotes;

        $this->assertEquals($this->totalYay, count($yays), "affirmativeVotes is correct count");
        foreach ($yays as $v) {
            $this->assertInstanceOf(Vote::class, $v, "Returned vote object");
        }

    }

    /** @test */
    public function negativeVotes()
    {
        $nays = $this->object->negativeVotes;
        $this->assertEquals($this->totalNay, count($nays), "negativeVotes is correct count");

        foreach ($this->object->negativeVotes as $v) {
            $this->assertInstanceOf(Vote::class, $v, "Returned vote object");
        }
    }

    /** @test */
    public function passedReturnsTrueWhenMajoritySatisfied()
    {
        // prep
        $this->totalNay = 4;
        $this->totalYay = 6;
        $this->object = Motion::factory()->majority()->create();
        $this->createVotes();

//        foreach ($this->votes as $vote) {
//            $vote->motion()->associate($this->object);
//            $vote->save();
//        }

        //call
        $result = $this->object->passed;

        //check
        $this->assertTrue($result, "Passed is true when yays are greater than majority threshold");
    }

    /** @test */
    public function passedReturnsFalseWhenMajorityNotSatisfied()
    {
        // prep
        $this->totalNay = 6;
        $this->totalYay = 4;
        $this->object = Motion::factory()->majority()->create();
        $this->createVotes();

        //call
        $result = $this->object->passed;

        //check
        $this->assertFalse($result, "Passed is false when nays are greater than majority threshold");
    }

    /** @test */
    public function passedReturnsFalseWithTie()
    {
        // prep
        $this->totalNay = 5;
        $this->totalYay = 5;
        $this->object = Motion::factory()->majority()->create();
        $this->createVotes();

        //call
        $result = $this->object->passed;

        //check
        $this->assertFalse($result, "Passed is false a tie (majority required)");
    }

    /** @test */
    public function passedReturnsTrueWhenTwoThirdsSatisfied()
    {
        // prep
        $this->totalNay = 3;
        $this->totalYay = 7;
        $this->object = Motion::factory()->twoThirds()->create();
        $this->createVotes();

        //call
        $result = $this->object->passed;

        //check
        $this->assertTrue($result, "Passed is true when yays are greater than 2/3 threshold");
    }

    /** @test */
    public function passedReturnsFalseWhenTwoThirdsNotSatisfied()
    {
        // prep
        $this->totalNay = 6;
        $this->totalYay = 4;
        $this->object = Motion::factory()->twoThirds()->create();
        $this->createVotes();

        //call
        $result = $this->object->passed;

        //check
        $this->assertFalse($result, "Passed is false when nays are greater than 2/3 threshold");
    }


    /** @test */
    public function requiresMajority()
    {
        $this->object = Motion::factory()->majority()->create();
        $this->createVotes();

        //check
        $this->assertEquals(0.5, $this->object->requires, "Expected threshold set by factory");
        $this->assertTrue($this->object->requiresMajority, "requiresMajority returns true");
        $this->assertFalse($this->object->requiresTwoThirds, "requiresTwoThirds returns false");

    }

    /** @test */
    public function requiresTwoThirds()
    {
        $this->object = Motion::factory()->twoThirds()->create();
        $this->createVotes();

        $this->assertEquals(0.66, $this->object->requires, "Expected threshold set by factory");
        $this->assertTrue($this->object->requiresTwoThirds, "requiresTwoThirds returns true");
        $this->assertFalse($this->object->requiresMajority, "requiresMajority returns false");
    }


    /** @test */
    public function totalVotesCast()
    {
        $total = $this->totalNay + $this->totalYay;

        $this->assertEquals($total, $this->object->totalVotesCast, "Correct number returned by totalVotesCast");
    }

    /** @test */
    public function voteCountThresholdWhenRequiresMajority()
    {

        $this->object = Motion::factory()->majority()->create();
        $this->createVotes();

//        foreach ($this->votes as $vote) {
//            $vote->motion()->associate($this->object);
//            $vote->save();
//        }

        $total = $this->totalYay + $this->totalNay;
        $expected = $total * 0.50;
        $result = $this->object->voteCountThreshold;
        //check
        $this->assertEquals($expected, $result, "voteCountThreshold returns correct threshold for a motion requiring a majority");

    }


// ---------------------- relationships
//
//    public function testAdministrators()
//    {
//
//    }
//
//
//    public function testVotes()
//    {
//
//    }


}
