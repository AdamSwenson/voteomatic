<?php

namespace App\Repositories;

use App\Models\Meeting;
use App\Models\Motion;
use App\Models\Vote;
use App\Repositories\ResolutionRepository;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class ResolutionRepositoryTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->object = new ResolutionRepository();

    }

    /** @test */
    public function handleApprovedAmendment()
    {
        $original = Motion::factory()->resolution()->create();
        $amendment = Motion::factory()->resolutionAmendment()->create(['applies_to' => $original]);

        $superseding = $this->object->handleApprovedAmendment($original, $amendment);

        //check
        //verify new motion
        $this->assertInstanceOf(Motion::class, $superseding, "Returns a motion");
        //stuff from amendment
        $this->assertEquals($amendment->content, $superseding->content, "Amended text set");

        //stuff from og
        $this->assertEquals($original->type, $superseding->type);
        $this->assertEquals($original->requires, $superseding->requires, "Original requires set");
        $this->assertEquals($original->description, $superseding->description, "Description set");
        $this->assertEquals($original->seconded, $superseding->seconded, "Seconded set");
        $this->assertEquals($original->applies_to, $superseding->applies_to, "Original applies to set");

        //rezzie specific
        //stuff from amendment
        $this->assertEquals(  $amendment->info['formattedContent'],$superseding->info['formattedContent'], "Original formattedContent set");

        //stuff from original
        $this->assertEquals($original->info['groupId'] , $superseding->info['groupId'], "Original group id set");
        $this->assertEquals($original->info['title'] , $superseding->info['title'], "Original title set");
        $this->assertEquals($original->info['resolutionIdentifier'] , $superseding->info['resolutionIdentifier'], "Original resolutionIdentifier set");

        //verify status changed on the original
        $this->assertEquals($original->superseded_by, $superseding->id, "Superseded by set on the original");

    }

    /** @test */
    public function handlePotentialAmendment()
    {
        $meeting = Meeting::factory()->create();
        $original = Motion::factory()->majority()->create(['meeting_id' => $meeting->id]);
        $amendment = Motion::factory()->amendment()->create(['applies_to' => $original, 'meeting_id' => $meeting->id]);

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
    public function handleRejectedAmendment()
    {
        $original = Motion::factory()->resolution()->create();
        $amendment = Motion::factory()->resolutionAmendment()->create(['applies_to' => $original]);

        $superseding = $this->object->handleRejectedAmendment($original, $amendment);

        //check
        //verify new motion
        $this->assertInstanceOf(Motion::class, $superseding, "Returns a motion");
        //Specific to resolution handling
        $this->assertEquals($original->content, $superseding->content, "Original text set as content");
        $this->assertEquals($amendment->info['formattedContent'] , $superseding->info['formattedContent'], "Amendment formatted content set as formattedContent");

        //stuff from og
        $this->assertEquals($original->type, $superseding->type);
        $this->assertEquals($original->requires, $superseding->requires, "Original requires set");
        $this->assertEquals($original->description, $superseding->description, "Description set");
        $this->assertEquals($original->seconded, $superseding->seconded, "Seconded set");
        $this->assertEquals($original->applies_to, $superseding->applies_to, "Original applies to set");

        //rezzie specific
        //stuff from amendment
        $this->assertEquals(  $amendment->info['formattedContent'],$superseding->info['formattedContent'], "Original formattedContent set");

        //stuff from original
        $this->assertEquals($original->info['groupId'] , $superseding->info['groupId'], "Original group id set");
        $this->assertEquals($original->info['title'] , $superseding->info['title'], "Original title set");
        $this->assertEquals($original->info['resolutionIdentifier'] , $superseding->info['resolutionIdentifier'], "Original resolutionIdentifier set");

        //verify status changed on the original
        $this->assertEquals($original->superseded_by, $superseding->id, "Superseded by set on the original");

    }

}
