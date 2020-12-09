<?php

namespace Tests\Repositories;

use App\Models\Meeting;
use App\Models\Motion;
use App\Repositories\MotionTreeRepository;
use Tests\helpers\AssignmentDataMaker;
use Tests\TestCase;
use function Tests\helpers\recursiveAdder;


class MotionTreeRepositoryTest extends TestCase
{
    public function setUp():void
    {
        parent::setUp();

        $this->object = new MotionTreeRepository();

    }

    public function recursiveAdder($motion, $meeting, $numChildren, $numLevels, $level)
    {
        for ($h = 0; $h < $numChildren; $h++) {
            $child = Motion::factory([
                'meeting_id' => $meeting->id,
                'applies_to' => $motion->id
            ])->create();
            //if we aren't as deep as we need to go,
            //repeat everything for the child

            $level += 1;
            if ($level <= $numLevels) {
                $this->recursiveAdder($child, $meeting, $numChildren, $numLevels, $level);
            }
        }
    }

    /** @test */
    public function loadMotionTree()
    {
        $numLevels = 3;
        $numChildren = 3;

        $meeting = Meeting::factory()->create();

        for ($i = 0; $i < $numChildren; $i++) {
            $topLevel = Motion::factory(['meeting_id' => $meeting->id])->create();
            $this->recursiveAdder($topLevel, $meeting, $numChildren, $numLevels, 0);

        }

        //call
        $result = $this->object->loadMotionTree($meeting);


        //check
        $this->assertEquals($numChildren * $numLevels, sizeOf($result), "correct number of motions returned");

    }
}
