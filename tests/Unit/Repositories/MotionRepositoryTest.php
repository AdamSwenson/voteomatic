<?php

namespace App\Repositories;

use App\Models\Motion;
use App\Repositories\MotionRepository;
use Tests\TestCase;

class MotionRepositoryTest extends TestCase
{

    public function setUp():void
    {
        parent::setUp();
        $this->object = new MotionRepository();
    }

    public function testHandleApprovedAmendment()
    {
        $original = Motion::factory()->create();
        $amendment = Motion::factory()->create();

        $superseding = $this->object->handleApprovedAmendment($original, $amendment);

        //check
        //verify new motion
        $this->assertInstanceOf(Motion::class, $superseding, "Returns a motion");
        $this->assertEquals($amendment->content, $superseding->content, "Amended text set");
        $this->assertEquals($original->type, $superseding->type);
        $this->assertEquals($original->requires, $superseding->requires, "Original requires set");
        $this->assertEquals($original->description, $superseding->description, "Description set");
        $this->assertEquals($original->seconded, $superseding->seconded, "Seconded set");
        $this->assertEquals($original->applies_to, $superseding->applies_to, "Original applies to set");

        //verify status changed on the original
        $this->assertEquals($original->superseded_by, $superseding->id, "Superseded by set on the original");

    }
}
