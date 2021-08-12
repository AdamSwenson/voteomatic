<?php

namespace App\Http\Controllers\Motion;

use App\Events\MotionClosed;
use App\Http\Controllers\Motion\MotionStackController;

//use PHPUnit\Framework\TestCase;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use App\Repositories\IMotionStackRepository;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class MotionStackControllerTest extends TestCase
{

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private $user;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private $meeting;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private $motion;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    private $nonMember;
    /**
     * @var string
     */
    public $url;


    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->meeting = Meeting::factory()->create();
        $this->meeting->addUserToMeeting($this->user);
        $this->motion = Motion::factory()->create(['meeting_id' => $this->meeting->id]);
        $this->nonMember = User::factory()->create();
    }

    /** @test */
    public function markMotionComplete()
    {
        $this->url = 'motions/close/' . $this->motion->id;

        $response = $this->actingAs($this->user)
            ->post($this->url);


        //check
        $response->assertSuccessful();

    }


    /** @test */
    public function markMotionCompleteDeniesNonMembers()
    {
        $this->url = 'motions/close/' . $this->motion->id;

        $response = $this->actingAs($this->nonMember)
            ->post($this->url);


        //check
        $response->assertStatus(403);
    }


    /** @test */
    public function markMotionCompleteDispatchesBroadcastEvent()
    {
        Event::fake();
        $this->url = 'motions/close/' . $this->motion->id;

        $response = $this->actingAs($this->user)
            ->post($this->url);

        $motion = $this->motion;

        Event::assertDispatched(MotionClosed::class);
        Event::assertDispatched(function (MotionClosed $event) use ($motion) {
            return $event->ended->is($motion) && $event->ended->is_complete === true && $event->ended->is_voting_allowed === false;
        });

    }

    /** @test */
    public function getCurrentMotion()
    {
        $this->mock(IMotionStackRepository::class)->shouldReceive('getCurrentMotion');
        $this->url = "motions/stack/" . $this->meeting->id;

        $response = $this->actingAs($this->user)
            ->get($this->url);

        //check
        $response->assertSuccessful();
    }

    /** @test */
    public function getCurrentMotionDeniesNonMember()
    {
        $this->mock(IMotionStackRepository::class)->shouldReceive('getCurrentMotion');
        $this->url = "motions/stack/" . $this->meeting->id;


        $response = $this->actingAs($this->nonMember)
            ->get($this->url);

        //check
        $response->assertStatus(403);

    }

    /** @test */
    public function setAsCurrentMotion()
    {
        $this->url = "motions/stack/" . $this->meeting->id . '/' . $this->motion->id;
        $response = $this->actingAs($this->user)
            ->post($this->url);


        //check
        $response->assertSuccessful();

    }


    /** @test */
    public function setAsCurrentMotionDeniesNonMembers()
    {
        $this->url = "motions/stack/" . $this->meeting->id . '/' . $this->motion->id;


        $response = $this->actingAs($this->nonMember)
            ->post($this->url);


        //check
        $response->assertStatus(403);

    }


//    /** @test */
//    public function storeOrder()
//    {
//
//        $this->mock(IAssignmentRepository::class)->shouldReceive('processIncoming');
//
//        $data = ['order' => 'taco'];
//
//        $this->url = "motions/stack/" . $this->meeting->id . '/'. $this->motion->id;
//        $response = $this->actingAs($this->user)
//            ->post($this->url);
//
//
//        //check
//        $response->assertSuccessful();
//
//
//    }

}
