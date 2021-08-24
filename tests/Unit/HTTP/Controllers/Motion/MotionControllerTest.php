<?php

namespace App\Http\Controllers\Motion;

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
    /**
     * @var string
     */
    public $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->chair()->create();

        $this->urlBase = '/motions';

        $this->meeting = Meeting::factory()->create();
        $this->user->meetings()->attach($this->meeting);
        $this->user->push();

        $this->motion = Motion::factory()->create();
        $this->meeting->motions()->save($this->motion);
        $this->meeting->push();

        $this->url = $this->urlBase . '/' . $this->motion->id;

    }

    /** @test */
    public function index()
    {
        $response = $this->actingAs($this->user)
            ->get($this->urlBase);


        $response->assertStatus(403, "No one gets to see every motion");
    }

    /** @test  */
    public function getAllForMeeting(){
        $this->markTestIncomplete();
    }

    /** @test  */
    public function getAllForMeetingAllowsMember(){
        $url = '/motions/meeting/' . $this->meeting->id;
        $response = $this->actingAs($this->user)->get($url);

        $response->assertSuccessful();
    }


    /** @test  */
    public function getAllForMeetingDenysNonMember(){
        $url = '/motions/meeting/' . $this->meeting->id;
        $response = $this->actingAs(User::factory()->create())->get($url);

        $response->assertStatus(403);
    }

    /** @test */
    public function storeWhenNoPreexistingBlankMotion()
    {
        //prep
        $motions = Motion::factory()->count(3)->create();
        foreach ($motions as $motion) {
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
        foreach ($motions as $motion) {
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
        $motionId = $this->motion->id;
        $payload = ['data' => ['content' => 'bar']];

        //call
        $response = $this->actingAs($this->user)
            ->put($this->url, $payload);

        //check
        $response->assertSuccessful();

        $this->motion->refresh();

        $motion = Motion::find($motionId);
        $this->assertEquals('bar', $motion->content);
//        $this->assertEquals('bar', $this->motion->name);

    }

    /** @test */
    public function updatePreventsNonChair()
    {
        $payload = ['data' => ['name' => 'bar']];

        $meeting = Meeting::factory()->create(['owner_id' => $this->user->id]);
        $nonChair = User::factory()->regUser()->create();

        //call
        $response = $this->actingAs($nonChair)
            ->put($this->url, $payload);

        //check
        $response->assertStatus(403);
    }

    /** @test */
    public function destroy()
    {
        $motionId = $this->motion->id;
        //call
        $response = $this->actingAs($this->user)
            ->delete($this->url);

        //check
        $response->assertSuccessful();

        $this->assertEmpty(Motion::find($motionId));


    }



    /** @test */
    public function destroyAllowsChair()
    {

        //call
        $response = $this->actingAs($this->user)
            ->delete($this->url);

        //check
        $response->assertSuccessful();
    }



    /** @test */
    public function destroyPreventsNonChair()
    {
        $nonChair = User::factory()->regUser()->create();

        //call
        $response = $this->actingAs($nonChair)
            ->delete($this->url);

        //check
        $response->assertStatus(403);
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
