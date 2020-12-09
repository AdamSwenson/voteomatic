<?php

namespace Tests\Models;

use App\Models\Meeting;
use App\Models\Motion;
use Tests\TestCase;

class MeetingTest extends TestCase
{

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public $obj;

    public function setUp():void
    {
        parent::setUp();
        $this->obj = Meeting::factory()->create();
    }

    /** @test */
    public function assignMotionMainMotion()
    {

        $this->obj->initializeAssignmentRoot();

        $motion = Motion::factory()->create();

        $depth = 4;

        //call
        $root = $this->obj->getAssignmentRoot();
        $this->obj->assignMotion($motion, $root->id, $depth);

        //check
        $this->assertDatabaseHas('assignments', [
            'meeting_id' => $this->obj->id,
            'parent_id' => $root->id,
            'motion_id' => $motion->id
        ]);
    }

    /** @test */
    public function resetAssignments()
    {

    }

    /** @test */
    public function getAssignmentRoot()
    {

    }

    /** @test */
    public function initializeAssignmentRoot()
    {

        $this->obj->initializeAssignmentRoot();

        $this->assertDatabaseHas('assignments', [
            'meeting_id' => $this->obj->id,
            'parent_id' => null,
            'motion_id' => null
            ]);
    }

    public function testUsers()
    {
        $this->markTestSkipped();
    }


    public function testMotions()
    {

        $this->markTestSkipped();
    }

    public function testResourceLink()
    {

        $this->markTestSkipped();
    }

    public function testAssignments()
    {

        $this->markTestSkipped();
    }

}
