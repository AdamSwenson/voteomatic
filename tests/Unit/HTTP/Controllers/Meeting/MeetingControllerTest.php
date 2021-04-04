<?php

namespace App\Http\Controllers\Meeting;

use App\Http\Controllers\Meeting\MeetingController;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class MeetingControllerTest extends TestCase
{

    public string $urlBase;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public $user;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private $meeting;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->chair()->create();

        $this->meeting = Meeting::factory()->create(['owner_id' => $this->user->id]);


        Auth::logIn($this->user);
        $this->urlBase = '/meetings';

        $this->url = $this->urlBase . '/' . $this->meeting->id;
    }
//
//    public function testUpdate()
//    {
//
//    }
//
//    public function testDestroy()
//    {
//
//    }
//
//    public function testIndex()
//    {
//
//    }

    /** @test */
    public function storeWhenNoPreexistingBlankMeeting()
    {
        //prep
        $meetings = Meeting::factory()
            ->count(3)
            ->create(['owner_id' => $this->user->id]);
        foreach ($meetings as $meeting) {
            $this->user->meetings()->attach($meeting);
        }
        $this->user->save();
        $countBefore = sizeOf($this->user->meetings()->get());


        //call
        $response = $this->actingAs($this->user)
//            ->withSession(['foo' => 'bar'])
            ->post($this->urlBase);

        //check
        $this->assertEquals(200, $response->status(), "Expected 200 response code returned");

        $expectedCount = $countBefore + 1;
        $countAfter = sizeOf($this->user->meetings()->get());

        $am = $this->user->meetings()->get();
        $this->assertEquals($expectedCount, $countAfter, "New meeting created");

//        $this->assertEquals($this->user->id, $actual);
    }

    /** @test */
    public function storeUsingPreexistingBlankMeeting()
    {

        //prep
        $meetings = Meeting::factory()->count(3)->create();
        foreach ($meetings as $meeting) {
            $meeting->setOwner($this->user);
            $meeting->addUserToMeeting($this->user);
//            $this->user->meetings()->attach($meeting);
        }
        $blankMeeting = Meeting::create();
        $blankMeeting->setOwner($this->user);
        $blankMeeting->addUserToMeeting($this->user);

        $countBefore = sizeOf($this->user->meetings()->get());

        //call
        $response = $this->actingAs($this->user)
            ->withSession(['foo' => 'bar'])
            ->post($this->urlBase);

        //check
        $this->assertEquals(200, $response->status(), "Expected 200 response code returned");

        //We are expecting the size not to have changed
        $countAfter = sizeOf($this->user->meetings()->get());

        $this->assertEquals($countBefore, $countAfter, "Number of meetings did not change .");

    }

    /** @test */
    public function storePreventsNonChair()
    {

        $nonChair = User::factory()->regUser()->create();

        //call
        $response = $this->actingAs($nonChair)
            ->withSession(['foo' => 'bar'])
            ->post($this->urlBase);

        //check
        $response->assertStatus(403);
    }


    /** @test */
    public function update()
    {
        $payload = ['data' => ['name' => 'bar']];

        //call
        $response = $this->actingAs($this->user)
            ->put($this->url, $payload);

        //check
        $response->assertSuccessful();

        $this->meeting->refresh();
        $this->assertEquals('bar', $this->meeting->name);

    }

    /** @test */
    public function updatePreventsNonOwner()
    {
        $meeting = Meeting::factory()->create(['owner_id' => $this->user->id]);
        $nonChair = User::factory()->regUser()->create();

        //call
        $response = $this->actingAs($nonChair)
            ->put($this->url, ['name' => 'bar']);

        //check
        $response->assertStatus(403);
    }

    /** @test */
    public function destroy()
    {
        //call
        $response = $this->actingAs($this->user)
            ->delete($this->url);

        //check
        $response->assertSuccessful();
    }


    /** @test */
    public function destroyPreventsNonOwner()
    {
        $nonChair = User::factory()->regUser()->create();

        //call
        $response = $this->actingAs($nonChair)
            ->delete($this->url);

        //check
        $response->assertStatus(403);
    }


}
