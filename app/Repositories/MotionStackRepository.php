<?php


namespace App\Repositories;


use App\Models\Meeting;
use App\Models\Motion;

class MotionStackRepository implements IMotionStackRepository
{



    /**
     * Returns the motion that is currently selected for the given
     * meeting
     * @param Meeting $meeting
     */
    public function getCurrentMotion(Meeting $meeting)
    {
        return $meeting->motions()->where('is_current', true)->first();

    }

    /**
     * Makes this the motion currently pending before the body
     * @param Meeting $meeting
     * @param Motion $motion
     */
    public function setAsCurrentMotion(Meeting $meeting, Motion $motion)
    {
        //first unset any other motion set as current
        $prevCurrent = $this->getCurrentMotion($meeting);
        /*
         * If the previous current is the one we are setting, it fails to mark it as
         * current. That causes VOT-79. Still not sure why that happens, but
         * checking to make sure they aren't identical solves VOT-79
         */
        if (!is_null($prevCurrent) && !$prevCurrent->is($motion)) {
            $prevCurrent->is_current = false;
            $prevCurrent->save();
        }

        //now set the motion as current
        $motion->is_current = true;
        $motion->save();
        $motion->fresh();

        return $motion;
    }


    /**
     * Returns the FILO motion stack for the meeting.
     * Includes current, pending, and voted on motions.
     *
     * The 0th motion is the most recently created motion
     *
     * @param Meeting $meeting
     */
    public function getMotionStack(Meeting $meeting)
    {
        return $meeting->motions()->orderBy('id', 'desc')->get();
    }


}
