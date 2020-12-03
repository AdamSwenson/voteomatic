<?php


namespace App\Http\Controllers\LTI;

use App\Http\Requests\LTIRequest;
use App\LTI\Authenticators\OAuthAuthenticator;
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


//use PHPUnit\Framework\TestCase;
class LTILaunchControllerTest extends TestCase
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

    public function setUp(): void
    {
        parent::setUp();
        $this->object = new LTILaunchController;

        $this->urlBase = 'http://localhost';

        $this->resourceLink = ResourceLink::factory()->create();
        $this->consumer = LTIConsumer::factory()->create();
        $this->resourceLink->ltiConsumer()->associate($this->consumer)->save();
        $this->meeting = $this->resourceLink->meeting;

        /** @var  path Path request goes to */
        $this->path = "/lti-entry/" . $this->meeting->id;

        /** @var  endpoint The fully specified url used for the signature */
        $this->endpoint = $this->urlBase . $this->path;

        $this->user = User::factory()->create();
        $this->payload = LTIPayloadMaker::makePayload($this->meeting, $this->endpoint, $this->resourceLink, $this->user);

        $this->expectedUrl = "/home/" . $this->meeting->id;

    }


//    public function testRequestHandling()
//    {
//                $lti = app()->make(LTI::class);
//
//        $prov = $lti->toolProvider();
//
//        $this->assertInstanceOf(ToolProvider::class, $prov);
//
//    }

    /**
     * Using this to debug
     */
    public function testHandleLaunchRequestMockedAuth()
    {
        $authMock = Mockery::mock(OAuthAuthenticator::class)
            ->shouldReceive('authenticate')
            ->andReturn(true);

        //call
        $response = $this->post($this->path, $this->payload);

        //check
        //Check that redirected correctly
        $response->assertRedirect($this->expectedUrl);

        //Check that student logged in
        $this->assertEquals($this->user->id, Auth::id(), "Expected student logged in");

    }

    /**
     * Test directly on the controller class
     */
//    public function testHandleLaunchRequestDirect()
//    {
//        //prep
////        $request = LTIRequest::create($this->payload);
//
//        $request = new LTIRequest();
//
//        foreach ($this->payload as $k => $v) {
//            $request[$k] = $v;
//        }
//
////        $resourceLink = ResourceLink::find($data['resource_link_id']);
////        $activity = $resourceLink->activity;
//
//        //call
//        $response = $this->object->handleLaunchRequest($request, $this->meeting);
//
//        //check
//        //Check that redirected correctly
//        $response->assertRedirect($this->expectedUrl);
//
//        //Check that student logged in
//        $this->assertEquals($this->user->id, Auth::id(), "Expected student logged in");
//
//
//    }


    /**
     * Tests method by making post request
     */
    public function testHandleLaunchRequestFullStack()
    {
        //prep

        //call
        $response = $this->post($this->path, $this->payload);

        //check
        //Check that redirected correctly
        $response->assertRedirect($this->expectedUrl);

        //Check that student logged in
        $this->assertEquals($this->user->id, Auth::id(), "Expected student logged in");

    }

/*
 ================================== Newer version ===========================
*/



/** @test */
public function handleMeetingLaunchRequestEverythingExists(){
    //call
    $response = $this->post($this->path, $this->payload);

    //check
    //Check that redirected correctly
    $response->assertRedirect($this->expectedUrl);

    //Check that student logged in
    $this->assertEquals($this->user->id, Auth::id(), "Expected user logged in");

}



    /** @test */
    public function handleMeetingLaunchRequestNewResourceLink(){
        $this->consumer = LTIConsumer::factory()->create();
        $this->meeting = Meeting::factory()->create();
        $this->expectedUrl = "/home/" . $this->meeting->id;

        /** @var  path Path request goes to */
        $this->path = "/lti-entry/" . $this->meeting->id;

        /** @var  endpoint The fully specified url used for the signature */
        $this->endpoint = $this->urlBase . $this->path;

        $resourceLinkId = $this->faker->sha1;

        $this->user = User::factory()->create();

        $this->payload = LTIPayloadMaker::specifyPayloadContents($this->endpoint, $this->consumer->consumer_key, $this->consumer->secret_key, $resourceLinkId, $this->user->user_id_hash, []);

        //call
        $response = $this->post($this->path, $this->payload);

        //check
        //Check that redirected correctly
        $response->assertRedirect($this->expectedUrl);

        //Check that student logged in
        $this->assertEquals($this->user->id, Auth::id(), "Expected user logged in");

    }


    /** @test */
    public function handleMeetingLaunchRequestBrandNew(){
        $this->consumer = LTIConsumer::factory()->create();
        $this->meeting = Meeting::factory()->create();
        $this->expectedUrl = "/home/" . $this->meeting->id;

        /** @var  path Path request goes to */
        $this->path = "/lti-entry/" . $this->meeting->id;

        /** @var  endpoint The fully specified url used for the signature */
        $this->endpoint = $this->urlBase . $this->path;

        $resourceLinkId = $this->faker->sha1;

        $userId = $this->faker->sha1;

        $this->payload = LTIPayloadMaker::specifyPayloadContents($this->endpoint, $this->consumer->consumer_key, $this->consumer->secret_key, $resourceLinkId, $userId, []);

        //call
        $response = $this->post($this->path, $this->payload);

        //check
        //Check that redirected correctly
        $response->assertRedirect($this->expectedUrl);

        //Check that student logged in
        $loggedIn = Auth::user();
        $this->assertEquals($userId, $loggedIn->user_id_hash, "Expected user logged in");

    }






}
