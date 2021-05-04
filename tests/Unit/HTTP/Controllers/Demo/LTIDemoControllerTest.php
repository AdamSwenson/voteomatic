<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Demo\LTIDemoController;

//use PHPUnit\Framework\TestCase;
use App\Http\Controllers\LTI\LTILaunchController;
use App\Http\Requests\LTIRequest;
use App\Models\LTIConsumer;
use App\Models\Meeting;
use App\Models\ResourceLink;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\helpers\LTIPayloadMaker;
use Tests\TestCase;

class LTIDemoControllerTest extends TestCase
{

    /**
     * @var string
     */
    public $path;
    /**
     * @var string
     */
    public $endpoint;
    public $meeting;
    /**
     * @var Collection|Model
     */
    public $consumer;
    /**
     * @var Collection|Model
     */
    public $resourceLink;
    /**
     * @var string
     */
    public $urlBase;
    /**
     * @var string
     */
    public $expectedUrl;
    public $payload;
    /**
     * @var Collection|Model
     */
    public $user;
    public $object;
    /**
     * @var string
     */
    public $chairPath;
    /**
     * @var string
     */
    public $memberPath;

    public function setUp(): void
    {
        $this->chairPath = "lti/chair-demo";
        $this->memberPath = "lti/member-demo";


        parent::setUp();
        $this->object = new LTIDemoController();

        $this->resourceLink = ResourceLink::factory()->create();
        $this->consumer = LTIConsumer::factory()->create();
        $this->resourceLink->ltiConsumer()->associate($this->consumer)->save();
        $this->meeting = Meeting::factory()->create();
        $this->user = User::factory()->create();
        $this->meeting->addUserToMeeting($this->user);
        $this->payload = LTIPayloadMaker::makePayload($this->meeting, $this->endpoint, $this->resourceLink, $this->user);



    }


//    public function testLaunchChairDemoCalledDirectly()
//    {
//
//        $response = $this->object->launchChairDemo(new LTIRequest());
//        //call
//
//    }

    /** @test */
    public function launchChairDemoFullStack()
    {
        $this->payload = LTIPayloadMaker::makePayload($this->meeting, $this->chairPath, $this->resourceLink, $this->user);

        //verify our user is not a chair
        $this->assertNull($this->user->is_admin, "user is not starting as a chair");

        //call
        $response = $this->post($this->chairPath, $this->payload);

        //check
        //Check that redirected correctly to lti entry point with new meeting
        $expectedMeetingId = $this->meeting->id + 1;
        $this->expectedUrl = "lti-entry/" . $expectedMeetingId;
        $response->assertRedirect($this->expectedUrl);

        //check that was made a chair
        $this->user->refresh();
        $this->assertTrue($this->user->is_admin, "user has been set as chair");

    }


    /** @test */
    public function launchMemberDemoFullStack()
    {
        //verify our user is not a chair
        $this->assertNull($this->user->is_admin, "user is not starting as a chair");

        //call
        $response = $this->post($this->memberPath, $this->payload);

        //check
        //Check that redirected correctly to lti entry point with new meeting
        $expectedMeetingId = $this->meeting->id + 1;
        $this->expectedUrl = "lti-entry/" . $expectedMeetingId;
        $response->assertRedirect($this->expectedUrl);

        //check that was not made a chair
        $this->user->refresh();
        $this->assertFalse($this->user->is_admin, "user is still not  chair");

    }


}
