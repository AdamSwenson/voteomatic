<?php

namespace App\Repositories;

use App\Models\Motion;

interface IMotionRepository
{
    /**
     * Called when a motion has been altered by a subsidary action
     * viz, an amendment. The altered motion will be superseded by a
     * brand new motion.
     * When a motion is superseded, it essentially disappears from anything
     * the user sees.
     *
     * This does not set the superseding motion as current. That should be
     * done elsewhere by calling MotionStackRepository->setAsCurrentMotion
     * with the output of this method
     *
     * @param Motion $original
     * @param Motion $amendment
     */
    public function handleApprovedAmendment(Motion $original, Motion $amendment);

    public function isAmendable(Motion $motion);
}
