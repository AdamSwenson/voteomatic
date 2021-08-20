<?php

namespace Tests\Http\Controllers\Election;

use App\Http\Controllers\Election\CandidateController;
use App\Http\Controllers\Election\CandidatePoolController;

//use PHPUnit\Framework\TestCase;
use App\Models\Election\Person;
use App\Models\Election\PoolMember;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use App\Repositories\Election\ICandidateRepository;
use Tests\TestCase;

class CandidatePoolControllerTest extends TestCase
{

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
    private $meeting;
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

        $this->object = new CandidatePoolController();

        $this->motion = Motion::factory()->create();
        $this->meeting = Meeting::factory()->create();
        $this->meeting->motions()->save($this->motion);
        $this->owner = User::factory()->create();
        $this->meeting->setOwner($this->owner);

    }
//
//    /** @test */
//    public function makePoolMemberResponse()
//    {
//
//    }


    /** @test */
    public function addPersonToPool()
    {
        $this->poolMember = PoolMember::factory()->create(['motion_id' => $this->motion->id]);

        $person = Person::factory()->create();
        $url = 'election/pool/' . $this->motion->id . '/' . $person->id;

        $this->mock(ICandidateRepository::class)
            ->shouldReceive('addPersonToPool')
            ->andReturn($this->poolMember);

        //call
        $response = $this->actingAs($this->owner)->post($url);

        //check
        $response->assertSuccessful();

        $expected = $this->object->makePoolMemberResponse($this->poolMember);
        $response->assertJson($expected);

    }


    /** @test */
    public function addPersonToPoolRejectsNonOwner()
    {
        $nonOwner = User::factory()->create();
        $this->meeting->adduserToMeeting($nonOwner);

        $person = Person::factory()->create();
        $url = 'election/pool/' . $this->motion->id . '/' . $person->id;

        //call
        $response = $this->actingAs($nonOwner)->post($url);

        //check
        $response->assertStatus(403);

    }



    /** @test */
    public function getCandidatePoolAllowsNonOwnerMember()
    {
        $nonOwner = User::factory()->create();
        $this->meeting->adduserToMeeting($nonOwner);

        $url= 'election/pool/'. $this->motion->id;
        $pool = PoolMember::factory()->count(3)->create(['motion_id' => $this->motion->id]);

        $response = $this->actingAs($nonOwner)->get($url);

        //check
        $response->assertSuccessful();

        $expected = [];
        foreach($pool as $m){
            $expected[] = $this->object->makePoolMemberResponse($m);
        }
        $response->assertJson($expected);

    }


    /** @test */
    public function getCandidatePoolDeniesNonMember()
    {
        $rando = User::factory()->create();

        $url= 'election/pool/'. $this->motion->id;
        $pool = PoolMember::factory()->count(3)->create(['motion_id' => $this->motion->id]);

        $response = $this->actingAs($rando)->get($url);

        //check
        $response->assertStatus(403);

    }



    /** @test */
    public function getCandidatePoolAllowsOwnerNonMember()
    {
        $url= 'election/pool/'. $this->motion->id;
        $pool = PoolMember::factory()->count(3)->create(['motion_id' => $this->motion->id]);

        $response = $this->actingAs($this->owner)->get($url);

        //check
        $response->assertSuccessful();

        $expected = [];
        foreach($pool as $m){
            $expected[] = $this->object->makePoolMemberResponse($m);
        }
        $response->assertJson($expected);

    }



}
