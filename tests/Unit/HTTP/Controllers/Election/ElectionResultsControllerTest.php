<?php

namespace Tests\Http\Controllers\Election;

use App\Exceptions\ElectionPhaseProhibition;
use App\Http\Controllers\Election\ElectionResultsController;

//use PHPUnit\Framework\TestCase;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use App\Repositories\Election\IElectionAdminRepository;
use App\Repositories\Election\IElectionResultsRepository;
use Tests\TestCase;

class ElectionResultsControllerTest extends TestCase
{

    public $office;
    /**
     * @var string
     */
    public $url;
    public $election;
    public $owner;
    public $regularUserMember;

    public function setUp(): void
    {
        parent::setUp();
        $this->owner = User::factory()->administrator()->create();

        $this->election = Meeting::factory()->election()->create();
        $this->election->setOwner($this->owner);

        //set election phase
        $this->election->phase = 'results';

        $this->office = Motion::factory()->electedOffice()->create(
                ['meeting_id' => $this->election->id,
            'is_complete' => true
        ]);
        $this->url = "election/{$this->office->id}/results";

        $this->regularUserMember = User::factory()->create();
        $this->election->addUserToMeeting($this->regularUserMember);
    }

    /** @test */
    public function getResultsAllowsMemberOwner()
    {
        $this->election->addUserToMeeting($this->owner);

        $results = ['dummy_results' => 'since we do not need to check this here'];
        $this->mock(IElectionResultsRepository::class)
            ->shouldReceive('getResultsForClient')
            ->andReturn($results);

        $response = $this->actingAs($this->owner)->get($this->url);

        //check
        $response->assertSuccessful();
        $response->assertJson($results);
    }



    /** @test */
    public function getResultsAllowsRegularVoter()
    {
        $results = ['dummy_results' => 'since we do not need to check this here'];
        $this->mock(IElectionResultsRepository::class)
            ->shouldReceive('getResultsForClient')
            ->andReturn($results);

        $response = $this->actingAs($this->regularUserMember)->get($this->url);

        //check
        $this->assertEquals('results', $this->election->phase);
        $response->assertSuccessful();
        $response->assertJson($results);
    }


    /** @test */
    public function getResultsDeniesNonMember()
    {
        $rando = User::factory()->create();
        $response = $this->actingAs($rando)->get($this->url);

        //check
        $response->assertStatus(403);
    }

    /** @test */
    public function getResultsAllowsNonMemberOwner()
    {
        $results = ['dummy_results' => 'since we do not need to check this here'];
        $this->mock(IElectionResultsRepository::class)
            ->shouldReceive('getResultsForClient')
            ->andReturn($results);

        $response = $this->actingAs($this->owner)->get($this->url);

        //check
        $response->assertSuccessful();
        $response->assertJson($results);
    }


    // ========================= regular member (newer tests using phase)

    /** @test */
    public function getResultsDeniesRegularMemberWhenSetup()
    {
        //The is_complete seems to be deprecated; running off of phases now
        //thus changing test (2024-02-16) to setup instead of incomplete
//        $this->office->is_complete = false;
//        $this->office->save();

        $this->election->phase = 'setup';
        $this->election->save();

        $response = $this->actingAs($this->regularUserMember)->get($this->url);

        //check
        //middleware prevents access during setup
        $response->assertStatus(ElectionPhaseProhibition::ERROR_CODE);
    }

    /** @test */
    public function getResultsDeniesRegularMemberWhenNominations()
    {
        //The is_complete seems to be deprecated; running off of phases now
        //thus changing test (2024-02-16) to nominations instead of incomplete
//        $this->office->is_complete = false;
//        $this->office->save();

        $this->election->phase = 'nominations';
        $this->election->save();

        $response = $this->actingAs($this->regularUserMember)->get($this->url);

        //check
        //middleware prevents access during nominations
        $response->assertStatus(ElectionPhaseProhibition::ERROR_CODE);
    }

    /** @test */
    public function getResultsDeniesRegularMemberWhenVoting()
    {
        //The is_complete seems to be deprecated; running off of phases now
        //thus changing test (2024-02-16) to voting instead of incomplete
//        $this->office->is_complete = false;
//        $this->office->save();

        $this->election->phase = 'voting';
        $this->election->save();

        $response = $this->actingAs($this->regularUserMember)->get($this->url);

        //check
        //Voting phase gets through the middleware, so this will catch the error from the policy
        $response->assertStatus(403);
    }

    /** @test */
    public function getResultsDeniesRegularMemberWhenClosed()
    {
        //The is_complete seems to be deprecated; running off of phases now
        //thus changing test (2024-02-16) to closed instead of incomplete
//        $this->office->is_complete = false;
//        $this->office->save();

        $this->election->phase = 'closed';
        $this->election->save();

        $response = $this->actingAs($this->regularUserMember)->get($this->url);

        //check
//        $response->assertStatus(403);
                $response->assertStatus(ElectionPhaseProhibition::ERROR_CODE);
    }

    /** @test */
    public function getResultsAllowsRegularMemberWhenResults()
    {
        $this->election->phase = 'results';
        $this->election->save();

        $results = ['dummy_results' => 'since we do not need to check this here'];
        $this->mock(IElectionResultsRepository::class)
            ->shouldReceive('getResultsForClient')
            ->andReturn($results);

        $response = $this->actingAs($this->regularUserMember)->get($this->url);

        //check
        $response->assertSuccessful();
        $response->assertJson($results);
    }


    // =================== owner (newer tests using phase)

    /** @test */
    public function getResultsDeniesOwnerWhenSetup()
    {
        //The is_complete seems to be deprecated; running off of phases now
        //thus changing test (2024-02-16) to setup instead of incomplete
//        $this->office->is_complete = false;
//        $this->office->save();

        $this->election->phase = 'setup';
        $this->election->save();

        $response = $this->actingAs($this->owner)->get($this->url);

        //check
        //Owner always gets through the middleware, so this will catch the error from the policy
        $response->assertStatus(403);

//        $response->assertStatus(ElectionPhaseProhibition::ERROR_CODE);
    }

    /** @test */
    public function getResultsDeniesOwnerWhenNominations()
    {
        //The is_complete seems to be deprecated; running off of phases now
        //thus changing test (2024-02-16) to nominations instead of incomplete
//        $this->office->is_complete = false;
//        $this->office->save();

        $this->election->phase = 'nominations';
        $this->election->save();

        $response = $this->actingAs($this->owner)->get($this->url);

        //check
        //Owner always gets through the middleware, so this will catch the error from the policy
        $response->assertStatus(403);

//        $response->assertStatus(ElectionPhaseProhibition::ERROR_CODE);
    }

    /** @test */
    public function getResultsDeniesOwnerWhenVoting()
    {
        //The is_complete seems to be deprecated; running off of phases now
        //thus changing test (2024-02-16) to voting instead of incomplete
//        $this->office->is_complete = false;
//        $this->office->save();

        $this->election->phase = 'voting';
        $this->election->save();

        $response = $this->actingAs($this->owner)->get($this->url);

        //check
        //Owner always gets through the middleware, so this will catch the error from the policy
        $response->assertStatus(403);

//        $response->assertStatus(ElectionPhaseProhibition::ERROR_CODE);
    }


    /** @test */
    public function getResultsAllowsOwnerWhenClosed()
    {
        //The is_complete seems to be deprecated; running off of phases now
        //thus changing test (2024-02-16) to closed instead of incomplete
//        $this->office->is_complete = false;
//        $this->office->save();

        $this->election->phase = 'closed';
        $this->election->save();

        $results = ['dummy_results' => 'since we do not need to check this here'];
        $this->mock(IElectionResultsRepository::class)
            ->shouldReceive('getResultsForClient')
            ->andReturn($results);

        $response = $this->actingAs($this->owner)->get($this->url);

        //check
        $response->assertSuccessful();
        $response->assertJson($results);
    }



    /** @test */
    public function getResultsAllowsOwnerWhenResults()
    {
        $this->election->phase = 'results';
        $this->election->save();

        $results = ['dummy_results' => 'since we do not need to check this here'];
        $this->mock(IElectionResultsRepository::class)
            ->shouldReceive('getResultsForClient')
            ->andReturn($results);

        $response = $this->actingAs($this->owner)->get($this->url);

        //check
        $response->assertSuccessful();
        $response->assertJson($results);
    }








    /** @test */
    public function getResultsDeniesOwnerWhenIncomplete()
    {
        $this->markTestSkipped("dev probably deprecated");

        $this->office->is_complete = false;
        $this->office->save();

        $response = $this->actingAs($this->owner)->get($this->url);

        //check
        $response->assertStatus(403);
    }

}
