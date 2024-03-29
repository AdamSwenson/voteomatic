<?php

namespace App\Repositories;

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
        $this->markTestSkipped('This repository is not used.');

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
        /*
         * Level : Expected count
         *  0 : 1                               Root
         *  1 : 3             Motion             Motion            Motion
         *  2 : 9        M      M     M            M   M   M         M    M    M
         *  3 : 27      MMM    MMM   MMM         MMM  MMM MMM       MMM  MMM  MMM
         *
         * total : 40
         *
         */
        $expected = 40;

        $this->assertEquals($expected, sizeOf($result), "correct number of motions returned");

    }
}
