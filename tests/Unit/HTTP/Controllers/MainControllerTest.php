<?php

namespace App\Http\Controllers;

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
//        $this->url = 'main/' . $this->motion->id;

    }




    /** @test */
    public function meetingHome()
    {
        $this->url = 'home/' . $this->meeting->id;

        //call
        $response = $this->actingAs($this->user)->get($this->url);

        //check
        $response->assertSuccessful();
        $response->assertViewIs('main');

    }

    /** @test */
    public function meetingHomeDeniesNonMembers()
    {
        $this->url = 'home/' . $this->meeting->id;
        $nonMember = User::factory()->create();

        //call
        $response = $this->actingAs($nonMember)->get($this->url);

        //check
        $response->assertStatus(403);


    }





//    dev This seems to be deprecated along with the method in the controller
//    /** @test */
//    public function getVotePage()
//    {
//        $this->url = 'main/' . $this->motion->id;
//
//        //call
//        $response = $this->actingAs($this->user)->get($this->url);
//
//        //check
//        $response->assertSuccessful();
//        $response->assertViewIs('main');
//
//    }
//
//    /** @test */
//    public function getVotePageDeniesNonMembers()
//    {
//        $this->url = 'main/' . $this->motion->id;
//
//        $nonMember = User::factory()->create();
//
//        //call
//        $response = $this->actingAs($nonMember)->get($this->url);
//
//        //check
//        $response->assertStatus(403);
//
//
//    }

}
