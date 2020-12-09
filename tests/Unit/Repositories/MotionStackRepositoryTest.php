<?php

namespace Tests\Repositories;

use App\Models\Meeting;
use App\Models\Motion;
use App\Repositories\MotionStackRepository;
use Tests\TestCase;

class MotionStackRepositoryTest extends TestCase
{

    public $meeting;
    public $motions;
    public $currentMotion;
    private $completedMotions;

    public function setUp():void
    {
        parent::setUp();

        $this->meeting = Meeting::factory()->create();
//        $this->motions = [];
        $this->completedMotions = Motion::factory()->completed()->count(4)->create(['meeting_id' => $this->meeting->id]);
        $this->motions = $this->completedMotions;
        $this->currentMotion = Motion::factory()->current()->create(['meeting_id' => $this->meeting->id]);
//        $this-> = Motion::factory()->count(2)->create(['meeting_id' => $meeting->id]);
        $this->motions[] = $this->currentMotion;
        $this->object = new MotionStackRepository();
    }

    public function testGetCurrentMotion()
    {
        $result = $this->object->getCurrentMotion($this->meeting);

        //check
        $this->assertEquals($this->currentMotion->id, $result->id, "Returns correct motion");

    }

    public function testGetMotionStack()
    {
        $result = $this->object->getMotionStack($this->meeting);

        $this->assertEquals(sizeOf($this->motions), sizeOf($result), "Correct number of motions returned. ");

        //check that in correct order
        $prevId = $this->currentMotion->id;
        foreach ($result as $r){
            $this->assertGreaterThanOrEqual($r->id, $prevId, "In descending order");
      $prevId = $r->id;
        }

    }

    /** @test */
    public function setAsCurrentMotionWhenNoneSet()
    {
        $meeting = Meeting::factory()->create();
        $motion = Motion::factory()->create(['meeting_id' => $meeting->id]);

        //call
        $this->object->setAsCurrentMotion($meeting, $motion);

        //check
        $this->assertTrue($motion->is_current, "Motion has been set");

    }



    /** @test */
    public function setAsCurrentMotionWhenAnotherSet()
    {
        $meeting = Meeting::factory()->create();
        $prevCurrent = Motion::factory()->current()->create(['meeting_id' => $meeting->id]);
        $motion = Motion::factory()->create(['meeting_id' => $meeting->id]);

        //call
        $this->object->setAsCurrentMotion($meeting, $motion);

        //check
        $this->assertFalse($prevCurrent->is_current, "Previous motion has been unset as current");

        $this->assertTrue($motion->is_current, "Motion has been set");

    }
}
