<?php

namespace Tests\Http\Controllers;

use App\Http\Controllers\MainController;

//use PHPUnit\Framework\TestCase;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use Tests\TestCase;

class MainControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->meeting = Meeting::factory()->create();
        $this->meeting->addUserToMeeting($this->user);
//        $this->meeting->users()->attach($this->user);
        $this->motion = Motion::factory()->create(['meeting_id' => $this->meeting->id]);
        $this->url = 'main/' . $this->motion->id;

    }

    /** @test */
    public function getVotePage()
    {

        //call
        $response = $this->actingAs($this->user)->get($this->url);

        //check
        $response->assertSuccessful();
        $response->assertViewIs('main');

    }

    /** @test */
    public function getVotePageDeniesNonMembers()
    {

        $nonMember = User::factory()->create();

        //call
        $response = $this->actingAs($nonMember)->get($this->url);

        //check
        $response->assertStatus(403);


    }

}