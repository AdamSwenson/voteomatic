<?php

namespace App\Repositories;

use App\Models\LTIConsumer;
use App\Models\Meeting;
use App\Models\ResourceLink;
use App\Repositories\MeetingRepository;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class MeetingRepositoryTest extends TestCase
{

    public function setUp():void
    {
        parent::setUp();

        $this->object = new MeetingRepository();

    }

    public function testCreateWithResourceLink()
    {

        $consumer = LTIConsumer::factory()->create();

        $attrs = ['name' => $this->faker->company()];

        //call
        $result = $this->object->createWithResourceLink($consumer->consumer_key, $attrs);

        //check
        $this->assertInstanceOf(Meeting::class, $result, "Returns expected object");
        $this->assertEquals($attrs['name'], $result->name, "Name set on meeting");

        $link = $result->resourceLink;
        $this->assertInstanceOf(ResourceLink::class, $link, "Created a resource link");
        $this->assertEquals($consumer->id, $link->lti_consumer_id, "Correct consumer id");
        $this->assertEquals($attrs['name'], $link->description, "Meeting name set as description ");


    }

}
