<?php

namespace App\Repositories;

use App\Models\LTIConsumer;
use App\Models\Meeting;
use App\Models\ResourceLink;
use App\Models\User;
use App\Repositories\MeetingRepository;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class MeetingRepositoryTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->object = new MeetingRepository();
        $this->user = User::factory()->create();
    }

    public function testCreateWithResourceLink()
    {
//todo Figure out if actually unused.
        $this->markTestSkipped('Method is likely unused');

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


    /** @test */
    public function createMeetingForUserWPreexistingBlank()
    {

        //prep
        $meetings = Meeting::factory()->count(3)->create();
        foreach ($meetings as $meeting) {
            $meeting->setOwner($this->user);
            $meeting->addUserToMeeting($this->user);
        }

        $blankMeeting = Meeting::create();
        $blankMeeting->setOwner($this->user);
        $blankMeeting->addUserToMeeting($this->user);

        $countBefore = sizeOf($this->user->meetings()->get());

        //call
        $result = $this->object->createMeetingForUser($this->user);

        //check
        $this->assertNotNull($result);

        $countAfter = sizeOf($this->user->meetings()->get());
        $this->assertEquals($countBefore, $countAfter, "Number of meetings did not change .");


        $this->assertTrue($blankMeeting->is($result), "We received the blank meeting");


    }

    /** @test */
    public function createMeetingForUserWNoBlank()
    {

        //prep
        $meetings = Meeting::factory()->count(3)->create();
        foreach ($meetings as $meeting) {
            $meeting->setOwner($this->user);
            $meeting->addUserToMeeting($this->user);
        }

        $countBefore = sizeOf($this->user->meetings()->get());

        //call
        $result = $this->object->createMeetingForUser($this->user);

        //check
        $this->assertNotNull($result);
        $this->assertInstanceOf(Meeting::class, $result);
        $countAfter = sizeOf($this->user->meetings()->get());
        $this->assertEquals($countBefore + 1, $countAfter, "Number of meetings changed .");

    }

    /** @test */
    public function getEmptyMeetingsForUser()
    {

        //prep
        $meetings = Meeting::factory()->count(3)->create();
        foreach ($meetings as $meeting) {
            $meeting->setOwner($this->user);
            $meeting->addUserToMeeting($this->user);
        }

        $blankMeeting = Meeting::create();
        $blankMeeting->setOwner($this->user);
        $blankMeeting->addUserToMeeting($this->user);

        //call
        $result = $this->object->getEmptyMeetingsForUser($this->user);

        //check
        $this->assertTrue($blankMeeting->is($result->first()), "We received the blank meeting");

    }


    /** @test */
    public function getEmptyMeetingsForUserWhenNoneExist()
    {

        //prep
        $meetings = Meeting::factory()->count(3)->create();
        foreach ($meetings as $meeting) {
            $meeting->setOwner($this->user);
            $meeting->addUserToMeeting($this->user);
        }

        //call
        $result = $this->object->getEmptyMeetingsForUser($this->user);

        //check
        $this->assertEquals(0, sizeof($result), "Empty collection returned");

    }


    /** @test */
    public function getEmptyMeetingsForUserWhenMultipleExist()
    {

        //prep
        $num = 3;
        for ($i = 0; $i < $num; $i++) {
            $meeting = Meeting::create();
            $meeting->setOwner($this->user);
            $meeting->addUserToMeeting($this->user);
        }

        //call
        $result = $this->object->getEmptyMeetingsForUser($this->user);

        //check
        $this->assertEquals($num, sizeof($result), "collection w expected entry count returned");

    }


}
