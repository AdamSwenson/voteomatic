<?php

namespace Tests\Http\Controllers;

use App\Http\Controllers\ResultsController;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use Tests\TestCase;

class ResultsControllerTest extends TestCase
{

    //todo authorization

    private $meeting;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private $motion;
    /**
     * @var string
     */
    private $path;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private $user;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private $nonMember;

    public function setUp():void
    {
        parent::setUp();
$this->meeting = Meeting::factory()->create();
        $this->motion = Motion::factory()->create(['meeting_id' => $this->meeting->id]);
        $this->path = '/results/' . $this->motion->id;


        $this->user = User::factory()->create();
        $this->nonMember = User::factory()->create();
        $this->meeting->addUserToMeeting($this->user);

    }

    /** @test */
    public function getResultsWithoutCounts()
    {
        //prep
//        $payload = ['counts' => false];

        //call
        $response = $this->actingAs($this->user)
            ->get($this->path); //, $payload);

        //check
        $response->assertStatus(200);
        $response->assertExactJson([
            'passed' => $this->motion->passed,
            'totalVotes' => $this->motion->totalVotesCast
        ]); //checking exact to make sure counts not sent


    }


    /** @test */
    public function getResultsWithoutCountsDeniesNonMember()
    {
        //call
        $response = $this->actingAs($this->nonMember)
            ->get($this->path);

        //check
        $response->assertStatus(403);
    }


    /** @test */
    public function getCounts()
    {
        //prep
        $path = $this->path . '/counts';

        //call
        $response = $this->actingAs($this->user)->get($path);

        //check
        $response->assertStatus(200)
            ->assertExactJson([
//                'passed' => $this->motion->passed,
//                'totalVotes' => $this->motion->totalVotesCast,
                'yayCount' => count($this->motion->affirmativeVotes),
                'nayCount'=> count($this->motion->negativeVotes)
            ]); //checking exact to make sure counts not sent

    }


    /** @test */
    public function getCountsDeniesNonMember()
    {
        //prep
        $path = $this->path . '/counts';

        //call
        $response = $this->actingAs($this->nonMember)->get($path);

        //check
        $response->assertStatus(403);
    }




}
