<?php

namespace Tests\Http\Controllers\Meeting;

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

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Auth::logIn($this->user);
        $this->urlBase = '/meetings';
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
        $meetings = Meeting::factory()->count(3)->create();
        foreach ($meetings as $meeting){
            $this->user->meetings()->attach($meeting);
        }
        $this->user->save();
        $countBefore = sizeOf($this->user->meetings()->get());


        //call
        $response = $this->actingAs($this->user)
            ->withSession(['foo' => 'bar'])
            ->post($this->urlBase);

        //check
        $this->assertEquals(200, $response->status(), "Expected 200 response code returned");

        $expectedCount = $countBefore + 1;
        $countAfter = sizeOf($this->user->meetings()->get());

        $am = $this->user->meetings()->get();
        $this->assertEquals($expectedCount, $countAfter, "New meeting created");

    }

    /** @test */
    public function storeUsingPreexistingBlankMeeting()
    {

        //prep
        $meetings = Meeting::factory()->count(3)->create();
        foreach ($meetings as $meeting){
            $this->user->meetings()->attach($meeting);
        }
        $blankMeeting = Meeting::create();
        $this->user->meetings()->attach($blankMeeting);
        $this->user->save();

        $countBefore = sizeOf($this->user->meetings()->get());

        //call
        $response = $this->actingAs($this->user)
            ->withSession(['foo' => 'bar'])
            ->post($this->urlBase);

        //check
        $this->assertEquals(200, $response->status(), "Expected 200 response code returned");

        //We are expecting the size not to have changed
        //$meetings + the blank meeting
        $expect = sizeOf($meetings) +1;
        $countAfter = sizeOf($this->user->meetings()->get());

        $this->assertEquals($countBefore, $countAfter, "No new meeting created");

    }

//
//    public function testShow()
//    {
//
//    }
}
