<?php

namespace Tests\Http\Controllers\Election;

use App\Http\Controllers\Election\CandidateController;

//use PHPUnit\Framework\TestCase;
use App\Models\Election\Candidate;
use App\Models\Election\Person;
use App\Models\Election\PoolMember;
use App\Models\Meeting;
use App\Models\User;
use App\Repositories\Election\ICandidateRepository;
use App\Repositories\IMotionStackRepository;
use Tests\TestCase;

class CandidateControllerTest extends TestCase
{

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public $meeting;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    private $poolMember;
    /**
     * @var \Illuminate\Support\HigherOrderCollectionProxy|mixed
     */
    private $motion;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    private $owner;
    /**
     * @var string
     */
    private $url;

    public function setUp(): void
    {
        parent::setUp();
        $this->object = new CandidateController();
        $this->poolMember = PoolMember::factory()->create();
        $this->motion = $this->poolMember->motion;
        $this->meeting = Meeting::factory()->create();
        $this->meeting->motions()->save($this->motion);
        $this->owner = User::factory()->create();
        $this->meeting->setOwner($this->owner);

        $this->url = 'election/nominate/' . $this->poolMember->id;
    }

    /** @test */
    public function addCandidateToBallot()
    {

        $candidate = Candidate::factory()->create();

        $this->mock(ICandidateRepository::class)
            ->shouldReceive('addCandidateToBallot')
//            ->withArgs([$motion, $poolMember->person])
            ->andReturn($candidate);

        $response = $this->actingAs($this->owner)->post($this->url);

        //check
        $response->assertSuccessful();

        $expected = $this->object->makeCandidateResponse($candidate);
        $response->assertJson($expected);
    }

    /** @test */
    public function addCandidateToBallotRejectsNonOwner()
    {
        $nonOwner = User::factory()->create();

        $response = $this->actingAs($nonOwner)->post($this->url);

        //check
        $response->assertStatus(403);

    }


//    /** @test */
//    public function makeCandidateResponse()
//    {
//
//    }

    /** @test */
    public function addWriteInCandidate()
    {
        $url = 'election/write-in/' . $this->motion->id;
        $this->meeting->addUserToMeeting($this->owner);

        $person = Person::factory()->create();
        $candidate = Candidate::factory()->writeIn()->create(['person_id' => $person->id, 'motion_id' => $this->motion->id]);

        $data = ['first_name' => $person->first_name, 'last_name' => $person->last_name,
            'info' => $person->info];
        $response = $this->actingAs($this->owner)->post($url, $data);

        //check
        $response->assertSuccessful();

//        $expected = $this->object->makeCandidateResponse($candidate);
//        $response->assertJson($expected);

    }

    /** @test */
    public function addWriteInCandidateAllowsNonOwner()
    {
        $url = 'election/write-in/' . $this->motion->id;
        $nonOwner = User::factory()->create();
        $this->meeting->addUserToMeeting($nonOwner);

        $person = Person::factory()->create();
        $candidate = Candidate::factory()->writeIn()->create(['person_id' => $person->id, 'motion_id' => $this->motion->id]);

        $data = ['first_name' => $person->first_name, 'last_name' => $person->last_name,
            'info' => $person->info];
        $response = $this->actingAs($nonOwner)->post($url, $data);

        //check
        $response->assertSuccessful();

//        $expected = $this->object->makeCandidateResponse($candidate);
//        $response->assertJson($expected);
    }

    /** @test */
    public function getOfficialCandidatesForOfficeAllowsOwnerWhenOwnerPartOfMeeting()
    {
        $this->meeting->addUserToMeeting($this->owner);
        $url = "election/{$this->motion->id}/candidates";
        $candidates = Candidate::factory()->count(3)->create();

        $this->mock(ICandidateRepository::class)
            ->shouldReceive('getOfficialCandidatesForOffice')
//            ->withArgs([$motion, $poolMember->person])
            ->andReturn($candidates);

        $response = $this->actingAs($this->owner)->get($url);

        //check
        $response->assertSuccessful();

        $expected = [];
        foreach ($candidates as $candidate) {
            $expected[] = $this->object->makeCandidateResponse($candidate);
        }
        $response->assertJson($expected);

    }

    /** @test */
    public function getOfficialCandidatesForOfficeAllowsMember()
    {
        $nonOwner = User::factory()->create();
        $this->meeting->adduserToMeeting($nonOwner);
        $url = "election/{$this->motion->id}/candidates";
        $candidates = Candidate::factory()->count(3)->create();

        $this->mock(ICandidateRepository::class)
            ->shouldReceive('getOfficialCandidatesForOffice')
//            ->withArgs([$motion, $poolMember->person])
            ->andReturn($candidates);

        $response = $this->actingAs($nonOwner)->get($url);

        //check
        $response->assertSuccessful();

        $expected = [];
        foreach ($candidates as $candidate) {
            $expected[] = $this->object->makeCandidateResponse($candidate);
        }
        $response->assertJson($expected);

    }


    /** @test */
    public function getOfficialCandidatesForOfficeDeniesNonMemberRegUser()
    {
        $nonOwner = User::factory()->create();
//        $this->meeting->addUserToMeeting($nonOwner);
        $url = "election/{$this->motion->id}/candidates";

        $response = $this->actingAs($nonOwner)->get($url);

        //check
        $response->assertStatus(403);
    }


    /** @test */
    public function getOfficialCandidatesForOfficeDeniesNonMemberOwner()
    {
        $rando = User::factory()->create();
        $this->meeting->setOwner($rando);
        $url = "election/{$this->motion->id}/candidates";

        $response = $this->actingAs($rando)->get($url);

        //check
        $response->assertStatus(403);
    }


    /** @test */
    public function removeCandidate()
    {
        $candidate = Candidate::factory()->create(['motion_id' => $this->motion->id]);
$url = 'election/candidate/' . $candidate->id;

        $response = $this->actingAs($this->owner)->delete($url);

        $response->assertSuccessful();
    }


    /** @test */
    public function removeCandidateRejectsNonOwnerMember()
    {
        $nonOwner = User::factory()->create();
        $this->meeting->addUserToMeeting($nonOwner);

        $candidate = Candidate::factory()->create(['motion_id' => $this->motion->id]);
        $url = 'election/candidate/' . $candidate->id;

        $response = $this->actingAs($nonOwner)->delete($url);

        //check
        $response->assertStatus(403);
    }

    /** @test */
    public function removeCandidateRejectsNonMember()
    {
        $rando = User::factory()->create();
        $this->meeting->addUserToMeeting($rando);

        $candidate = Candidate::factory()->create(['motion_id' => $this->motion->id]);
        $url = 'election/candidate/' . $candidate->id;

        $response = $this->actingAs($rando)->delete($url);

        //check
        $response->assertStatus(403);
    }

//    /** @test */
//    public function show()
//    {
//
//    }

}
