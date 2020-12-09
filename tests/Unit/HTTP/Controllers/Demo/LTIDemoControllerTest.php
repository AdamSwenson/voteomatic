<?php

namespace Tests\Http\Controllers\Demo;

use App\Http\Controllers\Demo\LTIDemoController;

//use PHPUnit\Framework\TestCase;
use App\Http\Controllers\LTI\LTILaunchController;
use App\Models\LTIConsumer;
use App\Models\Meeting;
use App\Models\ResourceLink;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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

    public function setUp():void
    {
        parent::setUp();
        $this->object = new LTILaunchController;

        $this->urlBase = 'http://localhost';

        $this->resourceLink = ResourceLink::factory()->create();
        $this->consumer = LTIConsumer::factory()->create();
        $this->resourceLink->ltiConsumer()->associate($this->consumer)->save();
$this->meeting = Meeting::factory()->create();
        $this->chairPath = $this->urlBase . "/lti/chair-demo";
        $this->memberPath = $this->urlBase . "/lti/member-demo";

        /** @var  endpoint The fully specified url used for the signature */
//        $this->endpoint = $this->urlBase . $this->path;

        $this->user = User::factory()->create();
        $this->payload = LTIPayloadMaker::makePayload($this->meeting, $this->endpoint, $this->resourceLink, $this->user);

        $this->expectedUrl = "/home/" . $this->meeting->id;

    }


    public function testLaunchMemberDemo()
    {

    }

    public function testLaunchChairDemo()
    {
        //call
        $response = $this->post('http://localhost/taco', $this->payload);

//        $response = $this->post($this->chairPath, $this->payload);

        //check
        //Check that redirected correctly
        $response->assertRedirect($this->expectedUrl);

        //Check that student logged in
        $this->assertEquals($this->user->id, Auth::id(), "Expected student logged in");


    }

}
