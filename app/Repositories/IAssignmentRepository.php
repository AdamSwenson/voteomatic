<?php

namespace App\Repositories;

use App\Models\Assignment;
use App\Models\Meeting;
use App\Models\Motion;

interface IAssignmentRepository
{
//    public static function recursiveMaker(Meeting $meeting, $motionId, $parentId, $depth, $maxLevels, $currentLevel);
//
//    /**
//     * Assigns a motion to a particular location in the
//     * hierarchy under the given meeting amd returns
//     * the Assignment object representing that location
//     *
//     * @param Meeting $meeting
//     * @param motion $motion
//     * @param $parentId
//     * @param $depth The child number of the motion
//     * @return Assignment
//     */
//    public static function makeAssignment(Meeting $meeting, Motion $motion, $parentId, $depth);
//
//    static function addChildren(Assignment $assignment, $numChildren = 5);
//
//    public function canBeSynced($record);
//
//    /**
//     * Stores the incoming array from the client in
//     * the database.
//     * The expected format of incoming is a list of arrays
//     * with the form
//     *      MeetingId
//     *      parentId The id of the motion or Meeting which is the parent
//     *      motionId The id of the motion in question
//     *      motionOrder The sibling order of the motion
//     * }
//     * @param Meeting $meeting
//     * @param $incoming
//     */
//    public function processIncoming(Meeting $meeting, $incoming);
//
//    /**
//     * This is used on page load to order the motions
//     * Returns an array:
//     *  [
//     *      'meeting' => $meeting,
//     *      'motionObjects' => $motionObjects,
//     *      'motionOrder' => $motionOrder
//     *  ];
//     *
//     * The motionOrder slot is an array of
//     *      [
//     *          'meetingId' => $meeting->id,
//     * 'motionId' => $motion->id,
//     * 'parentId' => $parentMotionId,
//     * 'motionOrder' => $meeting->position
//     * ];
//     * where parentId will be null for the topmost motion.
//     *
//     * @param Meeting $meeting
//     * @return array
//     */
//    public function getMotionTreeForClient(Meeting $meeting);
//
//    public function childrenGetter(Assignment $assignment);
//
//    /**
//     * Gets the order of motions for use by the
//     * system which creates the draft for export.
//     *
//     * This will be a flat array of
//     *
//     * @param Meeting $meeting
//     * @return array
//     */
//    public function getMotionOrderForDraftMinutes(Meeting $meeting);
}
