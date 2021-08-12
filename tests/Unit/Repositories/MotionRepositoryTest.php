<?php

namespace App\Repositories;

use App\Exceptions\IneligibleSecondAttempt;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use App\Models\Vote;
use App\Repositories\MotionRepository;
use Tests\TestCase;

class MotionRepositoryTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->object = new MotionRepository();
    }

    /** @test */
    public function handleApprovedAmendment()
    {
        $original = Motion::factory()->create();
        $amendment = Motion::factory()->amendment()->create(['applies_to' => $original]);

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



    /** @test */
    public function handlePotentialAmendment(){
        $original = Motion::factory()->majority()->create();
        $amendment = Motion::factory()->amendment()->create(['applies_to' => $original]);

        Vote::factory()->affirmative()->count(5)->create(['motion_id' => $amendment->id]);
        Vote::factory()->negative()->count(1)->create(['motion_id' => $amendment->id]);

        $this->assertTrue($amendment->isAmendment(), "preflight check is amendment");
        $this->assertTrue($amendment->passed, "preflight check passed");

        //call
        $superseding = $this->object->handlePotentialAmendment($amendment);

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
        $original = $original->fresh();
        $this->assertEquals($original->superseded_by, $superseding->id, "Superseded by set on the original");

    }


    /** @test */
    public function handlePotentialAmendmentReturnsFalseIfNotAmendment()
    {
        $amendment = Motion::factory()->create();

        $superseding = $this->object->handlePotentialAmendment($amendment);

        //check
        $this->assertFalse($superseding, "Returned false because not amendment");
    }

    /** @test */
    public function handlePotentialAmendmentReturnsFalseIfNotPassed()
    {
        $amendment = Motion::factory()->create();
        Vote::factory()->affirmative()->count(1)->create(['motion_id' => $amendment->id]);
        Vote::factory()->negative()->count(5)->create(['motion_id' => $amendment->id]);

        $superseding = $this->object->handlePotentialAmendment($amendment);

        //check
        $this->assertFalse($superseding, "Returned false because not passed");
    }




    /** @test */
    public function secondMotionRegularUser()
    {
        $maker = User::factory()->create();
        $second = User::factory()->create();
        $meeting = Meeting::factory()->create();
//        $meeting->setOwner($m)
        $motion = Motion::factory()->create([
            'author_id' => $maker->id,
            'meeting_id' => $meeting->id
        ]);

        $motion = $this->object->secondMotion($motion, $second);

        $this->assertTrue($motion->seconded);
        $this->assertEquals($second->id, $motion->seconder_id);
    }

    /** @test */
    public function secondMotionChair()
    {
        $maker = User::factory()->create();
//        $second = User::factory()->create();
        $meeting = Meeting::factory()->create();
        $meeting->setOwner($maker);
        $motion = Motion::factory()->create([
            'author_id' => $maker->id,
            'meeting_id' => $meeting->id
        ]);

        $motion = $this->object->secondMotion($motion, $maker);

        $this->assertTrue($motion->seconded);
        $this->assertEquals($maker->id, $motion->seconder_id);
    }


    /** @test */
    public function secondMotionRejectsSelfSeconding()
    {
        $this->expectException(IneligibleSecondAttempt::class);
        $maker = User::factory()->create();
        $second = User::factory()->create();
        $meeting = Meeting::factory()->create();
        $motion = Motion::factory()->create([
            'author_id' => $maker->id,
            'meeting_id' => $meeting->id
        ]);

        $motion = $this->object->secondMotion($motion, $maker);

    }


}
