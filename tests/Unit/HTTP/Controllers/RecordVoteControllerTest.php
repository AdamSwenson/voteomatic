<?php

namespace App\Http\Controllers;

use App\Exceptions\DoubleVoteAttempt;
use App\Exceptions\VoteSubmittedAfterMotionClosed;

use App\Http\Controllers\RecordVoteController;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use App\Repositories\IVoterEligibilityRepository;
use Tests\TestCase;

class RecordVoteControllerTest extends TestCase
{

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public $user;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public $motion;
    public string $url;

    public function setUp():void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->meeting = Meeting::factory()->create();
        $this->meeting->addUserToMeeting($this->user);
//        $this->meeting->users()->attach($this->user);
        $this->motion = Motion::factory()->create(['meeting_id' => $this->meeting->id]);
        $this->url = 'record-vote/' . $this->motion->id;

    }

    /** @test */
    public function recordVoteHappyPath()
    {
//
//        $eligibilityRepoMock = \Mockery::mock(IVoterEligibilityRepository::class);
//
//        $this->app->instance(IVoterEligibilityRepository::class, $eligibilityRepoMock);
//        $eligibilityRepoMock->shouldReceive('hasAlreadyVoted')
//                ->andReturn(false);
//
//        $eligibilityRepoMock->shouldReceive('recordVoted')
//            ->with($this->motion, $this->user)
//            ->once();

        $data = ['vote' => $this->faker->randomElement(['yay', 'nay'])];

        //call
        $response = $this->actingAs($this->user)
            ->post($this->url, $data);

        //check
        $this->assertEquals(201, $response->status(), "Expected 201 created response code returned");
        $recordedVotes = $this->motion->votes()->get();
        $this->assertEquals(sizeof($recordedVotes), 1, "1 vote recorded for motion");

    }


    /** @test */
    public function recordVoteAlreadyVoted()
    {

        $eligibilityRepoMock = \Mockery::mock(IVoterEligibilityRepository::class);
        $this->app->instance(IVoterEligibilityRepository::class, $eligibilityRepoMock);

        $eligibilityRepoMock->shouldReceive('hasAlreadyVoted')
////                ->with($motion)
//                ->once()
            ->andReturn(true);

        $data = ['vote' => $this->faker->randomElement(['yay', 'nay'])];

        //call
        $response = $this->actingAs($this->user)
            ->withSession(['foo' => 'bar'])
            ->post($this->url, $data);

        //check
        $this->assertEquals(DoubleVoteAttempt::ERROR_CODE, $response->status(), "Expected error code from exception returned");
        $recordedVotes = $this->motion->votes()->get();
        $this->assertEquals(sizeof($recordedVotes), 0, "No votes recorded for motion");


    }

    /** @test */
    public function recordVoteVoteClosed()
    {

        $this->motion = Motion::factory()->completed()->create();
        $this->url = 'record-vote/' . $this->motion->id;

        $data = ['vote' => $this->faker->randomElement(['yay', 'nay'])];

        //call
        $response = $this->actingAs($this->user)
            ->withSession(['foo' => 'bar'])
            ->post($this->url, $data);

        //check
        $this->assertEquals(VoteSubmittedAfterMotionClosed::ERROR_CODE, $response->status(), "Expected error code from exception returned");
        $recordedVotes = $this->motion->votes()->get();
        $this->assertEquals(sizeof($recordedVotes), 0, "No votes recorded for motion");

    }

    /** @test  */
    public function recordVoteNonMemberUser(){
        $data = ['vote' => $this->faker->randomElement(['yay', 'nay'])];

        //call
        $nonMember = User::factory()->create();
        $response = $this->actingAs($nonMember)
            ->post($this->url, $data);

        //check
        $response->assertStatus(403);

    }

}
