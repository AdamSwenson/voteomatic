<?php


namespace App\Repositories;


use App\Models\Motion;

class MotionRepository implements IMotionRepository
{
    /**
     * we will copy everything except these when
     * creating superseding motions.
     * NB, we don't copy is_current because that will possibly
     * create 2 current motions. That is the purview of the MotionStackRepository
     */
    public $nonCopiedFields = ['updated_at', 'created_at', 'id', 'is_current'];
    /**
     * @var IMotionStackRepository|mixed
     */
    public $stackRepo;

    public function __construct()
    {

        $this->stackRepo = app()->make(IMotionStackRepository::class);

    }

    /**
     * Called when a motion has been altered by a subsidary action
     * viz, an amendment. The altered motion will be superseded by a
     * brand new motion.
     * When a motion is superseded, it essentially disappears from anything
     * the user sees.
     *
     * This does not set the superseding motion as current. There may need to
     * be additional instructions from the client before that happens.
     * Thus that should be done elsewhere by
     * calling MotionStackRepository->setAsCurrentMotion
     * with the output of this method
     *
     * @param Motion $original
     * @param Motion $amendment
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

        /*
         * Mark the original superseded.
         */
        $original->markSuperseded($supersedingMotion);
//
//        /*
//         * Mark the superseding motion as current
//         */
//        $this->stackRepo->setAsCurrentMotion($meeting, $supersedingMotion);
//
        return $supersedingMotion;
    }

    /**
     * This will be called by the controller.
     * It handles determining whether it is an amendment,
     * looking up the original motion, checking whether the amendment passed
     * and creating a superseding motion.
     *
     * @param Motion $amendment
     * @return false
     */
    public function handleAmendment(Motion $amendment)
    {
        if (!$amendment->isAmendment()) {
            return false;
        }

        //We check whether the amendment passed. If it failed,
        //there's nothing we need to do
        if (!$amendment->passed) {
            return false;
        }

        //It passed. That means we need to
        //update the content of the pending motion
        //First, we find the amended motion
        $original = Motion::find($amendment->applies_to);

        $superseding = $this->handleApprovedAmendment($original, $amendment);

        return $superseding;

    }


    public function isAmendable(Motion $motion)
    {

    }

}
