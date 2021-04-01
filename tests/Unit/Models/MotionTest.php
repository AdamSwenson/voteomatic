<?php

namespace Tests\Unit\Models;

use App\Models\Motion;
use Tests\TestCase;

class MotionTest extends TestCase
{


    /** @test */
    public function addSubsidiaryMotion()
    {
        $primary = Motion::factory()->create();
        $subsidiary = Motion::factory()->create();

        //call
        $primary->addSubsidiaryMotion($subsidiary);

        //check
        $this->assertDatabaseHas('motions',
            [
                'id' => $subsidiary->id,
                'applies_to' => $primary->id
            ]);

    }


    /** @test */
    public function getSubsidiaryMotions()
    {
        $primary = Motion::factory()->create();
        $subsidiaries = Motion::factory()->count(3)->create();

        foreach ($subsidiaries as $subsidiary) {
            $primary->addSubsidiaryMotion($subsidiary);
        }

        $subsidiaryIds = $subsidiaries->pluck('id')->sortDesc()->all();

        //call
        $results = $primary->getSubsidiaryMotions();

        //check
        $this->assertEquals(sizeof($subsidiaries), sizeof($results));
        $m = collect($subsidiaryIds)->max() + 1;
        foreach ($results as $r) {
            $this->assertContains($r->id, $subsidiaryIds);
            $this->assertTrue($r->id < $m, "In FILO order");
            $m = $r->id;
        }

    }


//props
    public function testGetNegativeVotesAttribute()
    {
        $this->markTestSkipped();
    }


    public function testFactory()
    {
        $m = Motion::factory()->procedural()->create();
        $this->assertInstanceOf(Motion::class, $m);

    }

    public function testGetPassedAttribute()
    {

        $this->markTestSkipped();

    }

    public function testRecordedVoteRecord()
    {
        $this->markTestSkipped();
    }


    public function testGetAffirmativeVotesAttribute()
    {
        $this->markTestSkipped();

    }
}
