<?php

namespace Tests\Repositories\Vote;

use App\Models\Motion;
use App\Models\RecordedVoteRecord;
use App\Models\User;
use App\Models\Vote;
use App\Repositories\Vote\VoteManagementRepository;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;


class VoteManagementRepositoryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->obj = new VoteManagementRepository();

    }

    /** @test */
    public function abortVotingOnMotion()
    {
        //prep
        $motion = Motion::factory(['is_voting_allowed' => true])->create();
        $otherMotion = Motion::factory(['is_voting_allowed' => true])->create();

        $cnt = 4;
        $voteToRemove = Vote::factory(['motion_id' => $motion->id])->create();
        $voteToKeep = Vote::factory(['motion_id' => $otherMotion->id])->create();

        $recordToRemove = RecordedVoteRecord::factory(['motion_id' => $motion->id, 'user_id'=> User::factory()->create()->id])->create();
        $recordToKeep = RecordedVoteRecord::factory(['motion_id' => $otherMotion->id, 'user_id'=> User::factory()->create()->id])->create();

        //call
        $this->obj->abortVotingOnMotion($motion);

        //check
//        foreach($votesToKeep as $vote){
            $this->assertModelExists($voteToKeep);
//        }
//        foreach($votesToRemove as $vote){
            $this->assertModelMissing($voteToRemove);
//        }
//        foreach($recordsToRemove as $record){
            $this->assertModelMissing($recordToRemove);
//        }
//        foreach($recordsToKeep as $record){
            $this->assertModelExists($recordToKeep);
//        }

    }
}
