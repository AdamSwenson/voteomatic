<?php

namespace Tests\Models\Election;

use App\Models\Election\Candidate;

//use PHPUnit\Framework\TestCase;
use App\Models\Motion;
use Tests\TestCase;

class CandidateTest extends TestCase
{

    private $motion;
    private $official;
    /**
     * @var int
     */
    private $num;
    private $writeIns;


    public function setUp(): void
    {
        parent::setUp();

        $this->object = Candidate::factory()->create();

        $this->motion = Motion::factory()->create();

        $this->num = 5;

        $this->writeIns = Candidate::factory()->writeIn()->count($this->num)->create([
            'motion_id' => $this->motion->id
        ]);

        $this->official = Candidate::factory()->count($this->num)->create([
            'motion_id' => $this->motion->id
        ]);


    }

    public function testScopeOfficial()
    {

        $this->assertEquals($this->num*2, sizeof($this->motion->candidates), "All candidates returned");

        $official = $this->motion->candidates()->official()->get();
        $this->assertEquals($this->num, sizeof($official), "Correct number of non write ins");

        foreach ($official as $r){
            $this->assertNotTrue($r->is_write_in, "boolean set properly");
        }
    }

    public function testScopeWriteIn()
    {

        $results = $this->motion->candidates()->writeIn()->get();

        $this->assertEquals($this->num, sizeof($results), "Correct number of non write ins");

        foreach ($results as $r){
            $this->assertNotTrue($r->is_write_in, "boolean set properly");
        }

    }

//    public function testGetTotalVotesReceivedAttribute()
//    {
//
//    }
//
//    public function testGetVoteTotal()
//    {
//
//    }

}
