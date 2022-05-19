<?php

namespace App\Repositories;

use App\Models\Motion;

class ResolutionRepository implements IResolutionRepository
{
    /**
     * @var IMotionStackRepository|mixed
     */
    public mixed $motionStackRepo;

    public function __construct()
    {
        $this->motionStackRepo = app()->make(IMotionStackRepository::class);

    }

    /**
     * we will copy everything except these when
     * creating superseding motions.
     * NB, we don't copy is_current because that will possibly
     * create 2 current motions. That is the purview of the MotionStackRepository
     */
    public $nonCopiedFields = ['updated_at', 'created_at', 'id', 'is_current'];

    /**
     * This will be called by the controller.
     * It handles determining whether the motion is an amendment,
     * looking up the original motion, checking whether the amendment passed
     * and creating a superseding motion if needed.
     *
     * @param Motion $amendment
     * @return false|Motion
     */
    public function handlePotentialAmendment(Motion $amendment)
    {
        //Non subsidiary motions need not apply
        if (!$amendment->isAmendment()) {
            return false;
        }

        //First, we find the amended motion
        $original = Motion::find($amendment->applies_to);

        //We check whether the amendment passed. If it failed,
        //we need to create a new shell motion so as to not
        //mess up pmode display
        if (!$amendment->passed) {
            $superseding = $this->handleRejectedAmendment($original, $amendment);
        } else {
            $superseding = $this->handleApprovedAmendment($original, $amendment);
        }

//dev probably a bad idea since with regular motions we do this separately after pusher has told the client about the changes
        $meeting = $superseding->meeting;
        $this->motionStackRepo->setAsCurrentMotion($meeting, $superseding);
        return $superseding;

    }

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
    public function handleApprovedAmendment(Motion $original, Motion $amendment)
    {
        /*
         * Create a  new motion with the amended text which supersedes the pending
         * motion.
         */
        $atrs = collect($original->attributesToArray())->except($this->nonCopiedFields);
        //add the new content from the approved amendment
        $atrs['content'] = $amendment->content;

        $supersedingMotion = Motion::create($atrs->toArray());

        if (isset($amendment->info['formattedContent'])) {
            $supersedingMotion->info['formattedContent'] = $amendment->info['formattedContent'];
        }

        $supersedingMotion->save();

        /*
         * Mark the original superseded.
         */
        $original->markSuperseded($supersedingMotion);

        return $supersedingMotion;
    }


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
    public function handleRejectedAmendment(Motion $original, Motion $amendment)
    {
        /*
         * Create a  new motion with the amended text which supersedes the pending
         * motion.
         */
        $atrs = collect($original->attributesToArray())->except($this->nonCopiedFields);

        //keep the original content since the amendment wasn't approved
        $atrs['content'] = $original->content;

        $supersedingMotion = Motion::create($atrs->toArray());

        //Use the amendment's formatted content as the new formatted content since
        //this will contain the defeated motion
        if (isset($amendment->info['formattedContent'])) {
            $supersedingMotion->info['formattedContent'] = $amendment->info['formattedContent'];
        }

        $supersedingMotion->save();

        /*
         * Mark the original superseded.
         */
        $original->markSuperseded($supersedingMotion);

        return $supersedingMotion;
    }

    /**
     * After a motion is created from the request by MotionRepository
     * this is called to add any resolution specific properties, etc
     * @param Motion $motion
     */
    public function initializeResolution(Motion $motion)
    {

            //For identifying related motions on a rezzie
//        if($motion->is_resolution && ! array_key_exists('groupId', $motion->info)){
            if (!isset($motion->info['groupId'])) {
                $motion->info['groupId'] = $motion->id;
                $motion->save();
            }

            return $motion;
//            //dev in caes the incoming is an amendment we need to copy the formatted content for VOT-197
//            if (!isset($motion->info['groupId'])) {
//                $motion->info['groupId'] = $motion->id;
//                $motion->save();
//            }

//        }
    }
}
