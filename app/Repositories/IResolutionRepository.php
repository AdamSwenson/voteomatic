<?php

namespace App\Repositories;

use App\Models\Motion;

interface IResolutionRepository
{
    /**
     * Called when a motion has been altered by a subsidary action
     * viz, an amendment. The altered motion will be superseded by a
     * brand new motion.
     * When a motion is superseded, it essentially disappears from anything
     * the user sees.
     *
     * This does NOT check that the amendment passed. That must
     * be done elsewhere before calling this.
     *
     * This does not set the superseding motion as current. There may need to
     * be additional instructions from the client before that happens.
     * Thus that should be done elsewhere by
     * calling MotionStackRepository->setAsCurrentMotion
     * with the output of this method
     *
     * @param Motion $original
     * @param Motion $amendment
     * @return mixed
     */
    public function handleApprovedAmendment(Motion $original, Motion $amendment);

    /**
     * For resolutions, we need to create a new shell motion when things
     * fail as well as when they pass to avoid losing amendments in the pmode
     * display
     *
     *
     * Called when a motion has been altered by a subsidary action
     * viz, an amendment. The altered motion will be superseded by a
     * brand new motion.
     * When a motion is superseded, it essentially disappears from anything
     * the user sees.
     *
     * This does NOT check that the amendment passed. That must
     * be done elsewhere before calling this.
     *
     * This does not set the superseding motion as current. There may need to
     * be additional instructions from the client before that happens.
     * Thus that should be done elsewhere by
     * calling MotionStackRepository->setAsCurrentMotion
     * with the output of this method
     *
     * @param Motion $original
     * @param Motion $amendment
     * @return mixed
     */
    public function handleRejectedAmendment(Motion $original, Motion $amendment);
}
