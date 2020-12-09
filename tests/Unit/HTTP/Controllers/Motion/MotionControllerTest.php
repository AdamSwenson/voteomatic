<?php

namespace Tests\Http\Controllers\Motion;

use App\Http\Controllers\Motion\MotionController;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class MotionControllerTest extends TestCase
{

    public string $urlBase;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public $user;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public $meeting;

    public function setUp():void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Auth::logIn($this->user);
        $this->urlBase = '/motions';
        $this->meeting = Meeting::factory()->create();
    }

//
//    public function testStore()
//    {
//
//    }


    /** @test */
    public function storeWhenNoPreexistingBlankMotion()
    {
        //prep
        $motions = Motion::factory()->count(3)->create();
        foreach ($motions as $motion){
            $this->meeting->motions()->save($motion);
        }
        $this->meeting->save();
        $countBefore = sizeOf($this->meeting->motions()->get());


        //call
        $data = ['meetingId' => $this->meeting->id];

        $response = $this->actingAs($this->user)
            ->withSession(['foo' => 'bar'])
            ->post($this->urlBase, $data);

        //check
        $this->assertEquals(200, $response->status(), "Expected 200 response code returned");

        $expectedCount = $countBefore + 1;
        $countAfter = sizeOf($this->meeting->motions()->get());

        $am = $this->meeting->motions()->get();
        $this->assertEquals($expectedCount, $countAfter, "New Motion created");

    }

    /** @test */
    public function storeUsingPreexistingBlankMotion()
    {

        //prep
        $motions = Motion::factory()->count(3)->create();
        foreach ($motions as $motion){
            $this->meeting->motions()->save($motion);
        }
        $blankMotion = motion::create();
        $this->meeting->motions()->save($blankMotion);
        $this->meeting->save();

        $countBefore = sizeOf($this->meeting->motions()->get());

        //call
        $data = ['meetingId' => $this->meeting->id];
        $response = $this->actingAs($this->user)
            ->withSession(['foo' => 'bar'])
            ->post($this->urlBase, $data);

        //check
        $this->assertEquals(200, $response->status(), "Expected 200 response code returned");

        //We are expecting the size not to have changed
        $countAfter = sizeOf($this->meeting->motions()->get());

        $this->assertEquals($countBefore, $countAfter, "No new motion created");

    }


//    public function testShow()
//    {
//
//    }
//
//    public function testGetAllForMeeting()
//    {
//
//    }
//
//    public function testDestroy()
//    {
//
//    }

//
//    public function testUpdate()
//    {
//
//    }
//
//    public function testCreateMotion()
//    {
//
//    }
}
