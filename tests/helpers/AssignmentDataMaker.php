<?php


namespace Tests\helpers;

use App\Models\Assignment;
use App\Models\Meeting;
use App\Models\Motion;
//use App\User;
//use Illuminate\Support\Facades\Auth;


/**
 * Class AssignmentDataMaker
 * Creates test data for relationships between motions in
 * an meeting
 *
 * @package Tests\helpers
 */
class AssignmentDataMaker
{



    static public function addChild(Assignment $assignment, Meeting $meeting, $position)
    {
        $motion = Motion::factory()->create();
        $assign = Assignment::create([
            'meeting_id' => $meeting->id,
            'motion_id' => $motion->id
        ]);

        return $assignment->addChild($assign, $position, true);

    }


    /**
     * Creates a full tree of motions for the given meeting
     * Returns the root Assignment object (whose parentId is null)
     *
     * @param $meeting
     * @param int $numLevels
     * @param int $numChildren
     * @return mixed
     */
    public function makeMeetingData($meeting, $numLevels = 3, $numChildren = 3, $save=true)
    {
        $rootMotion = Motion::factory()->create(); //standin for meeting1
        $parentAssignment = Assignment::create([
            'meeting_id' => $meeting->id,
            'motion_id' => $rootMotion->id
        ]);

        function recursiveAdder($assignment, $meeting, $numChildren, $numLevels, $level, $save)
        {
            for ($h = 0; $h < $numChildren; $h++) {
                //todo added the position variable when bug fixing. need to check that didn't mess things up
                $child = AssignmentDataMaker::addChild($assignment, $meeting, $h);

                //if we aren't as deep as we need to go,
                //repeat everything for the child

                if($save){
                    $assignment->push();
                    $child->save();
                }

                $level += 1;
                if ($level <= $numLevels) {
                    recursiveAdder($child, $meeting, $numChildren, $numLevels, $level, $save);
                }
            }
        }

        recursiveAdder($parentAssignment, $meeting, $numChildren, $numLevels, 0, $save);

        return $parentAssignment;
    }


    static public function makeOrderJsonData(Meeting $meeting, $numLevels = 3, $numAtLevel = 3)
    {
        $order = [];

        if (!$meeting) Meeting::factory()->create();

        $root = Motion::factory()->create(); //standin for meeting1

        for ($level = 0; $level < $numLevels; $level++) {

            for ($h = 0; $h < $numAtLevel; $h++) {
                $motion = Motion::factory()->create();
                $order[] = [
                    'meetingId' => $meeting->id,
                    'parentId' => $root->id,
                    'motionId' => $motion->id,
                    'motionOrder' => $h];
            }
            //On the last time through, we skip
            //Otherwise, we make children
            if ($level < $numLevels) {
                for ($j = 0; $j < $numAtLevel; $j++) {
                    $child = Motion::factory()->create();
                    $order[] = [
                        'meetingId' => $meeting->id,
                        'parentId' => $motion->id,
                        'motionId' => $child->id,
                        'motionOrder' => $j];
                }
            }
        }
        return $order;
    }


}
