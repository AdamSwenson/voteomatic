<?php

namespace App\Http\Controllers\Motion;

use App\Http\Controllers\Motion\MotionSecondController;

//use PHPUnit\Framework\TestCase;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use Tests\TestCase;

class MotionSecondControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->chair()->create();

        $this->urlBase = 'motions/second';
        $this->meeting = Meeting::factory()->create();
        $this->meeting->addUserToMeeting($this->user);
//        $this->user->meetings()->attach($this->meeting);
//        $this->user->push();

        $this->motionAuthor = User::factory()->create();
        $this->motion = Motion::factory()->create(['author_id' => $this->motionAuthor->id]);
        $this->meeting->motions()->save($this->motion);
        $this->meeting->push();

        $this->url = $this->urlBase . '/' . $this->motion->id;


    }

    /** @test */
    public function markMotionSeconded()
    {
        $this->assertFalse($this->motion->seconded, "Starting un-seconded");

        //call
        $response = $this->actingAs($this->user)->post($this->url);

        //check
        $response->assertSuccessful();
        $this->motion->refresh();

        $this->assertTrue($this->motion->seconded, "Updated as seconded");
    }


    /** @test */
    public function markMotionSecondedDeniesNonMember()
    {
        $nonMember = User::factory()->create();
        $this->assertFalse($this->motion->seconded, "Starting un-seconded");

        //call
        $response = $this->actingAs($nonMember)->post($this->url);

        //check
        $response->assertStatus(403);

    }


    /** @test */
    public function markNoSecondObtained()
    {
        $this->assertFalse($this->motion->seconded, "Starting un-seconded");

        //call
        $response = $this->actingAs($this->user)->post($this->url);

        //check
        $response->assertSuccessful();
        $this->motion->refresh();

        $this->assertTrue($this->motion->seconded, "Updated as seconded");
    }


    /** @test */
    public function markNoSecondObtainedDeniesNonChair()
    {
        $nonMember = User::factory()->create();
        $this->assertFalse($this->motion->seconded, "Starting un-seconded");

        //call
        $response = $this->actingAs($nonMember)->post($this->url);

        //check
        $response->assertStatus(403);

    }




}
