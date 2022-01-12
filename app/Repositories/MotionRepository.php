<?php


namespace App\Repositories;


use App\Exceptions\IneligibleSecondAttempt;
use App\Http\Requests\MotionRequest;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use http\Env\Request;

class MotionRepository implements IMotionRepository
{

    const PUSHER_BYTE_LIMIT = 10240;


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

    static public function isPusherCompatible(Motion $motion){
       return mb_strlen($motion->toJson()) <= self::PUSHER_BYTE_LIMIT;
    }

    public function createMotion(User $user, Meeting $meeting, MotionRequest $request)
    {

        //Since we are creating the motion without
        //the fields filled in, we may have blank motions
        //in the database. Thus we will try to reuse an existing empty
        //motion object before actually creating a new one
        $motion = Motion::where('meeting_id', $meeting->id)
            ->where('content', null)
            ->where('description', null)
//                ->where('requires', null)
            ->where('type', null)
            ->where('is_complete', false)
            ->first();

        if (!is_null($motion)) {
            //if we found a motion that was empty,
            //take whatever data we've been sent and set it
            //on the motion
            $motion->update($request->all());
            $motion->save();
        } else {
            //No preexisting empty motion associated with the meeting
            //has been found
            $motion = Motion::create($request->all());
        }

        //dev Perhaps we should make sure that is_complete and seconded are not yet set

        //Add it to the meeting
        //Note this will be harmlessly redundant if we
        //are using the preexisting object
        $meeting->motions()->save($motion);

        //Add the user creating the request's id
        //This is only needed to determine if the second is eligible
        $motion->setAuthor($user);

        return $motion;

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

        /*
         * Mark the original superseded.
         */
        $original->markSuperseded($supersedingMotion);

        return $supersedingMotion;
    }

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

    /**
     * Handles checking that the user is not identical to the author and
     * marking the motion seconded.
     * Does not check that the user is a member of the meeting. That should be
     * handled elsewhere.
     *
     * @param Motion $motion
     * @param User $second
     * @throws IneligibleSecondAttempt
     */
    public function secondMotion(Motion $motion, User $second)
    {
        //This is now handled by middleware,
        //but just in case...
        if (!$motion->isEligibleToSecond($second)) {
            throw new IneligibleSecondAttempt();
        }

        $motion->seconded = true;
        $motion->setSecond($second);
        $motion->save();
        return $motion;
    }


    public function markInOrder(Motion $motion, User $user)
    {
        $motion->approver_id = $user->id;
        $motion->is_in_order = true;
        $motion->save();

        return $motion;
    }

    public function markOutOfOrder(Motion $motion, User $user)
    {
        $motion->approver_id = $user->id;
        $motion->is_in_order = false;
        $motion->save();
        return $motion;
    }
}
