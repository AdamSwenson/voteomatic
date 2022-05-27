<?php

namespace Tests\Repositories\Election;

use App\Models\Election\Candidate;
use App\Models\Motion;
use App\Repositories\Election\ElectionVoteRepository;

//use PHPUnit\Framework\TestCase;
use Database\Factories\Election\CandidateFactory;
use Tests\TestCase;

class ElectionVoteRepositoryTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->motion = Motion::factory()->electedOfficeSingleChoice()->create();
        $this->candidates = Candidate::factory()->count(5)->create(['motion_id' => $this->motion->id]);
$this->object = new ElectionVoteRepository();
    }

    /** @test */
    public function recordOfficeVotesSingleCandidate()
    {

        $candidateIds = [$this->candidates[0]->id];

        $result = $this->object->recordOfficeVotes($this->motion, $candidateIds);

        $this->assertNotEmpty($result);
        $this->assertTrue(strlen($result) > 5);
    }




    /** @test */
    public function recordOfficeVotesMultipleCandidates()
    {
        $candidateIds = [];
        foreach($this->candidates as $candidate){
            $candidateIds[] = $candidate->id;
        }

        $this->motion->max_winners = sizeof($candidateIds);
        $this->motion->save();
//        $candidateIds = [$this->candidates[0]->id];

        $result = $this->object->recordOfficeVotes($this->motion, $candidateIds);

        $this->assertNotEmpty($result);
        $this->assertTrue(strlen($result) > 5);
//$this->markTestIncomplete('dev todo ');
    }



    /** @test */
    public function recordOfficeVotesFailsOnOverselection()
    {
        $this->markTestIncomplete('dev todo ');

    }


    /** @test */
    public function recordOfficeVotesFailsOnInvalidCandidate()
    {
        $this->markTestIncomplete('dev todo ');

    }


    /** @test */
    public function recordOfficeVotesFailsOnInvalidMotionId()
    {
        $this->markTestIncomplete('dev todo ');

    }
}
