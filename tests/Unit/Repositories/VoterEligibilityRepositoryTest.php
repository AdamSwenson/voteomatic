<?php

namespace App\Repositories;

use App\Exceptions\DoubleVoteAttempt;
use App\Models\Motion;
use App\Models\RecordedVoteRecord;
use App\Models\User;
use App\Repositories\VoterEligibilityRepository;
use Tests\TestCase;

//use PHPUnit\Framework\TestCase;

class VoterEligibilityRepositoryTest extends TestCase
{


    public function setUp():void
    {
        parent::setUp();
        $this->object = new VoterEligibilityRepository();
    }

    /** @test */
    public function hasAlreadyVotedWhenHasNotYetVoted()
    {
        $user = User::factory()->create();
        $motion = Motion::factory()->create();

        //call
        $result = $this->object->hasAlreadyVoted($motion, $user);

        //check
        $this->assertFalse($result, "False when have not yet voted. ");

    }

    /** @test */
    public function hasAlreadyVotedWhenHasVoted()
    {
        //creating the user since the recorded vote record factory
        //needs to soft-make the user (otherwise slows down the seeder
        //in the demo version)
        $user = User::factory()->create();
        $preexisting = RecordedVoteRecord::factory()->create(
            ['user_id' => $user->id]
        );
        $motion = $preexisting->motion;

        //call
        $result = $this->object->hasAlreadyVoted($motion, $user);

        //check
        $this->assertTrue($result, "True when already voted");
    }


    /** @test */
    public function isEligibleRaisesExceptionWhenAlreadyVoted()
    {
        //creating the user since the recorded vote record factory
        //needs to soft-make the user (otherwise slows down the seeder
        //in the demo version)
        $user = User::factory()->create();
        $preexisting = RecordedVoteRecord::factory()->create(
            ['user_id' => $user->id]
        );
        $motion = $preexisting->motion;

        $this->expectException(DoubleVoteAttempt::class);

        //call
        $result = $this->object->isEligible($motion, $user);

        //check
//        $this->assertTrue($result, "True when already voted");


    }

    /** @test */
    public function recordVoted()
    {
        $user = User::factory()->create();
        $motion = Motion::factory()->create();

        //call
        $result = $this->object->recordVoted($motion, $user);

        //check
        $this->assertDatabaseHas('recorded_vote_records', [
            'user_id' => $user->id,
            'motion_id' => $motion->id
        ]);

//        $this->assertFalse($result, "False when have not yet voted. ");


    }
}
