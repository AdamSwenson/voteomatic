<?php


namespace App\Repositories;

use App\Models\Meeting;
use App\Models\Assignment;
use App\Models\Motion;

class AssignmentRepository implements IAssignmentRepository
{


    public function addMainMotionToTree(Meeting $meeting, Motion $motion){
        $root = $meeting->getAssignmentRoot();

        //get the existing top level motions
        $topLevel = $meeting->assignments()->where('parent_id', );

    }


    public function addSubsidiaryMotionToTree(Meeting $meeting, Motion $pendingMotion, Motion $subsidiaryMotion){
        $subsidiaryMotion->applies_to = $pendingMotion->id;
        $subsidiaryMotion->save();
    }












    static public function recursiveMaker(Meeting $meeting, $motionId, $parentId, $depth, $maxLevels, $currentLevel)
    {

        if ($currentLevel < $maxLevels) {
            $motionAssignment = new Assignment(['motion_id' => $motionId, 'meeting_id' => $meeting->id]);
            $motionAssignment->save();
            $meeting->assignMotion($motionAssignment, $parentId, $depth);
        }
    }


    /**
     * Assigns a motion to a particular location in the
     * hierarchy under the given meeting amd returns
     * the Assignment object representing that location
     *
     * @param Meeting $meeting
     * @param motion $motion
     * @param $parentId
     * @param $depth The child number of the motion
     * @return Assignment
     */
    static public function makeAssignment(Meeting $meeting, Motion $motion, $parentId, $depth)
    {
//        $motionAssignment = new Assignment(['motion_id' => $motion->id]);
//        $motionAssignment->save();
        $motionAssignment = $meeting->assignMotion($motion, $parentId, $depth);
        return $motionAssignment;
    }

    static function addChildren(Assignment $assignment, $numChildren = 5)
    {
        for ($k = 0; $k < $numChildren; $k++) {
            $ec = new Assignment(['motion_id' => Motion::factory()->create()->id]);
            $assignment->addChild($ec);
        }
        $assignment->save();
        return $assignment;
    }

    public function canBeSynced($record)
    {
        if ($record['motionId'] === -1) return false;

        if ($record['meetingId'] === $record['motionId'] && $record['position'] === 0) return false;

        return true;
    }

    /**
     * Stores the incoming array from the client in
     * the database.
     *
     * The expected format of incoming is a list of arrays
     * with the form
     *      MeetingId
     *      parentId The id of the motion or Meeting which is the parent
     *      motionId The id of the motion in question
     *      position The sibling order of the motion
     * }
     * @param Meeting $meeting
     * @param $incoming
     */
    public function processIncoming(Meeting $meeting, $incoming)
    {
        //We start by deleting all of the existing assignments
        //for the Meeting.
        //todo Wrap in a transaction so this can be rolled back if there is an error
        $meeting->resetAssignments();

        //Process the incoming array
        foreach ($incoming as $record) {
            //check to make sure an unready motion hasn't slipped by
            if ($this->canBeSynced($record)) {

                //find the motion
                $motion = Motion::where('id', $record['motionId'])->first();

                if ($motion) {
                    $depth = $record['position'];
                    $meeting->assignMotion($motion, $record['parentId'], $depth);
                }
            }
        }
    }
    //To clean up, we need to remove a record which was
    //a side effect todo and likely an indication of problems!
    //of the processing.
    //This record was created on the first pass through the incoming array
    //which always includes the Meeting as its root.
    //We simply put the Meeting id in as the motion id.
    //But this can't serve as the root of the tree,
    //since an motion and Meeting could have the same id (they are
    //in different tables).
    // So we solved it by creating a
    //new motion without a parent as the root which has
    //the Meeting as its Meeting. This stands in for the Meeting
    //Thus when we query the Meeting assignments the root motion
    //is this second record.
    //
    //For now, we've left the original record in up to this point.
    //Eventually we will want to avoid creating it in the first place.
    //So let's remove it.
//        Assignment::where('motion_id', $Meeting->id)
//            ->where('Meeting_id' , $Meeting->id)
//            ->delete();


    /**
     * This returns the motion tree, i.e., which motions are subsidiary
     * to each other
     * Returns an array of
     *     [
     * 'meetingId' => $meeting->id,
     * 'motionId' => $motion->id,
     * 'parentId' => $parentMotionId,
     * 'position' => $meeting->position
     * ];
     * where parentId will be null for the topmost motion.
     *
     * @param Meeting $meeting
     * @return array
     */
    public function getMotionTreeForClient(Meeting $meeting)
    {
        $toEagerLoad = []; // 'comments', 'tags'];

//        $motionObjects = [];
        $position = [];

        $assignments = Assignment::where('meeting_id', $meeting->id)->get();

        return $assignments;

//
//        foreach ($assignments as $assignment) {
//            $motion = Motion::with($toEagerLoad)
//                ->where('id', $assignment->motion_id)
//                ->first();
//
//            if ($motion) {
////                $motionObjects[] = $motion;
//                $parentMotionAssignment = $assignment->getParent();//Assignment::where('parent_id', $meeting->parent_id)->first();
//
//                //set to null if topmost motion
//                $parentMotionId = $parentMotionAssignment ? $parentMotionAssignment->motion_id : null;
//
//                $position[] = [
//                    'meetingId' => $meeting->id,
//                    'motionId' => $motion->id,
//                    'parentId' => $parentMotionId,
//                    'position' => $assignment->position,
//                    'motionAssignmentId' => $assignment->id,
//                ];
//            }
//        }
//
//        return $position;

//        return [
//            'meeting' => $meeting,
//            'motionObjects' => $motionObjects,
//            'position' => $position
//        ];

    }


    public function childrenGetter(Assignment $assignment)
    {
        return $assignment->getChildren()->sortBy('position');

    }

    /**
     * Gets the order of motions for use by the
     * system which creates the draft for export.
     *
     * This will be a flat array of
     *
     * @param Meeting $meeting
     * @return array
     */
    public function getMotionTreeForDraftMinutes(Meeting $meeting)
    {
        $out = [];

        $rootAssignment = $meeting->getAssignmentRoot();

        function r($assignment, &$out){
            $assignments = $assignment->getChildren()->sortBy('position');
            foreach($assignments as $a){
                //push the new parent into the out array
                $out[] = $a;
                //and call the function recursively
                r($a, $out);
            }
        }

        r($rootAssignment, $out);

        return $out;

    }

}
