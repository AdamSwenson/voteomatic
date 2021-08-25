<?php

namespace Tests\Http\Controllers\Election;

use App\Exceptions\DoubleVoteAttempt;
use App\Exceptions\ExcessCandidatesSelected;
use App\Exceptions\VoteSubmittedAfterMotionClosed;
use App\Http\Controllers\Election\ElectionVoteController;

//use PHPUnit\Framework\TestCase;
use App\Models\Election\Candidate;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\RecordedVoteRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class ElectionVoteControllerTest extends TestCase
{

    public $owner;
    public $election;
    public $office;
    public $regularUserMember;
    /**
     * @var string
     */
    public $url;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public $candidates;
    /**
     * @var array
     */
    public $requestData;

    public function setUp(): void
    {
        parent::setUp();
        $this->owner = User::factory()->administrator()->create();

        $this->election = Meeting::factory()->election()->create();
        $this->election->setOwner($this->owner);

        $this->office = Motion::factory()->electedOffice()->create(
            ['meeting_id' => $this->election->id]);

        $this->url = "election/vote/{$this->office->id}";

        $this->regularUserMember = User::factory()->create();
        $this->election->addUserToMeeting($this->regularUserMember);

        $this->candidates = Candidate::factory()->count(5)->create(['motion_id' => $this->office->id]);

        $selectedCandidateIds = collect($this->faker->randomElements($this->candidates))->pluck('id');
        $this->office->max_winners = sizeof($selectedCandidateIds);
        $this->office->save();

        $this->requestData = ['candidateIds' => $selectedCandidateIds,
            'writeIns' => []
        ];

    }

    /** @test */
    public function recordVote()
    {

        $response = $this->actingAs($this->regularUserMember)->post($this->url, $this->requestData);

//check
        $response->assertSuccessful();
    }

    /** @test */
    public function recordVoteAllowsMemberOwner()
    {
        $this->election->addUserToMeeting($this->owner);
        $response = $this->actingAs($this->owner)->post($this->url, $this->requestData);

//check
        $response->assertSuccessful();
    }


    /** @test */
    public function recordVoteDeniesNonMemberOwner()
    {

        $response = $this->actingAs($this->owner)->post($this->url, $this->requestData);

        //check
        $response->assertStatus(403);
    }

    /** @test */
    public function recordVoteDeniesRegularMemberWhenAlreadyVoted()
    {

        $rec = new RecordedVoteRecord();
        $rec->motion_id = $this->office->id;
        $rec->user_id = $this->regularUserMember->id;
        $rec->save();
        $response = $this->actingAs($this->regularUserMember)->post($this->url, $this->requestData);

        //check
        $response->assertStatus(DoubleVoteAttempt::ERROR_CODE);
    }

    /** @test */
    public function recordVoteDeniesMemberOwnerWhenAlreadyVoted()
    {
        $this->election->addUserToMeeting($this->owner);

        $rec = new RecordedVoteRecord();
        $rec->motion_id = $this->office->id;
        $rec->user_id = $this->owner->id;
        $rec->save();

        $response = $this->actingAs($this->owner)->post($this->url, $this->requestData);

        //check
        $response->assertStatus(DoubleVoteAttempt::ERROR_CODE);
    }

    /** @test */
    public function recordVoteDeniesRegularMemberWhenElectionClosed()
    {
        $this->office->is_complete = true;
        $this->office->save();

        $response = $this->actingAs($this->regularUserMember)
            ->post($this->url, $this->requestData);

        //check
        $response->assertStatus(VoteSubmittedAfterMotionClosed::ERROR_CODE);
    }

    /** @test */
    public function recordVoteDeniesOwnerMemberWhenElectionClosed()
    {
        $this->election->addUserToMeeting($this->owner);

        $this->office->is_complete = true;
        $this->office->save();

        $response = $this->actingAs($this->owner)
            ->post($this->url, $this->requestData);

        //check
        $response->assertStatus(VoteSubmittedAfterMotionClosed::ERROR_CODE);
    }

    /** @test */
    public function recordVoteDeniesWhenTooManySelections()
    {
        $this->requestData['candidateIds'][] = 345;
        $this->requestData['candidateIds'][] = 922;
        $this->office->max_winners = 1;
        $this->office->save();

        $response = $this->actingAs($this->regularUserMember)
            ->post($this->url, $this->requestData);

        $response->assertStatus(ExcessCandidatesSelected::ERROR_CODE);
    }
}
