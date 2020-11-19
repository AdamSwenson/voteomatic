<?php

namespace Database\Seeders;

use App\Models\Assignment;

use App\Models\Meeting;
use App\Models\Motion;

use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{

    public $numAtLevel = 2;
    public $numLevels = 2;

    /**
     * Takes an meeting and Assignment and gives it
     * the number of children at the level
     *
     * @param Meeting $meeting
     * @param Assignment $parentAssign
     * @param $numberChildren
     */
    static public function createLevel(Meeting $meeting, Assignment $parentAssign, $numberChildren)
    {
        $assignments = [];

        $motions = Motion::factory()->count($numberChildren)->create();

        $depth = 0;
        foreach ($motions as $motion) {
            //Create the assignment for the new motion
            $assignment = $meeting->assignMotion($motion, $parentAssign->id, $depth);

//
//            $assignment = Assignment::create([
//                'meeting_id' => $meeting->id,
//                'motion_id' => $motion->id,
//            ]);
            //and associate it with its proud parent
//            $parentAssign->addChild($assignment);
//
            $assignments[] = $assignment;

            $depth += 1;
        }
        return $assignments;
    }


    /**
     * Populates a given meeting with new motions
     *
     * @param $meeting
     * @param $numLevels
     * @param $numAtLevel
     */
    static public function populateMeeting(Meeting $meeting, $numLevels, $numAtLevel)
    {
        //initialize the meeting's assignments. This will
        //create the root assignment (the Assignment which ties
        //the meeting to its immediate children)
        $meeting->resetAssignments();

        $meetingAssignment = $meeting->getAssignmentRoot();

        //at the top level, we just add motions to the meeting
        $assignments = self::createLevel($meeting, $meetingAssignment, $numAtLevel);

        for ($level = 1; $level <= $numLevels; $level++) {

//            if ( $level === 0 ) {
//                //at the top level, we just add motions to the meeting
//                $assignments = self::createLevel($meeting, $meetingAssignment, $numAtLevel);
//
//            } else {
            //if we're not at the top level, we can take a
            //shortcut by noticing that real_depth is equal to $level
            //So we can load all the motion assignments at the current level


//                $assignments = Assignment::where('meeting_id', $meeting->id)
//                    ->where('depth', $level)
//                    ->get();

            //iterate through the assignments that were created in the last round
            foreach ($assignments as $assignment) {
                //give each assignment its very own children
                //which we redefine as the new assignments array
                $assignments = self::createLevel($meeting, $assignment, $numAtLevel);
            }
//            }
        }
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::table('assignments')->delete();

        //create a new meeting
        $meeting = Meeting::factory()->create();

        self::populateMeeting($meeting, $this->numLevels, $this->numAtLevel);

        //
    }
}
