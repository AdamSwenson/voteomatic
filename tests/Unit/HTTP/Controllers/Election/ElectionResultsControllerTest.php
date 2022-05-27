<?php

namespace Tests\Http\Controllers\Election;

use App\Http\Controllers\Election\ElectionResultsController;

//use PHPUnit\Framework\TestCase;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
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


    /** @test */
    public function getResultsDeniesRegularMemberWhenIncomplete()
    {
        $this->office->is_complete = false;
        $this->office->save();

        $response = $this->actingAs($this->regularUserMember)->get($this->url);

        //check
        $response->assertStatus(403);
    }

    /** @test */
    public function getResultsDeniesOwnerWhenIncomplete()
    {
        $this->office->is_complete = false;
        $this->office->save();

        $response = $this->actingAs($this->owner)->get($this->url);

        //check
        $response->assertStatus(403);
    }

}
