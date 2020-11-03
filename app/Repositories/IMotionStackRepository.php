<?php

namespace App\Repositories;

use App\Models\Meeting;
use App\Models\Motion;

interface IMotionStackRepository
{
    /**
     * Returns the motion that is currently selected for the given
     * meeting
     * @param Meeting $meeting
     */
    public function getCurrentMotion(Meeting $meeting);

    /**
     * Makes this the motion currently pending before the body
     * @param Meeting $meeting
     * @param Motion $motion
     */
    public function setAsCurrentMotion(Meeting $meeting, Motion $motion);

    /**
     * Returns the FILO motion stack for the meeting.
     * Includes current, pending, and voted on motions.
     *
     * The 0th motion is the most recently created motion
     *
     * @param Meeting $meeting
     */
    public function getMotionStack(Meeting $meeting);
}
