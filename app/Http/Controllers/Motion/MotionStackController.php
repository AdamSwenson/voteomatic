<?php

namespace App\Http\Controllers\Motion;

use App\Events\MotionClosed;
use App\Events\NewCurrentMotionSet;
use App\Events\VotingOnMotionOpened;
use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Motion;
use App\Repositories\IMotionRepository;
use App\Repositories\IMotionStackRepository;
use Illuminate\Http\Request;

/**
 * Handles the order of motions
 *
 * Class MotionStackController
 * @package App\Http\Controllers\Motion
 */
class MotionStackController extends Controller
{
    /**
     * @var IMotionStackRepository|mixed
     */
    public $motionStackRepo;

    /**
     * @var IMotionRepository|mixed
     */
    public $motionRepo;

    public function __construct()
    {

        $this->middleware('auth');

        $this->motionStackRepo = app()->make(IMotionStackRepository::class);
        $this->motionRepo = app()->make(IMotionRepository::class);
    }


    /**
     * @param Motion $motion
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function markMotionComplete(Motion $motion)
    {
        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

        $this->authorize('markComplete', $motion);

        $motion->is_complete = true;
        $motion->save();

        //this will return false if not an amendment
        //otherwise it will return a new motion which has been amended with
        //the motion passed in
        $superseding = $this->motionRepo->handleAmendment($motion);

        //We would set the superseding motion as current here
        //Not doing so that people on the client can view the results
        //and then manually select the superseding.
        //todo That means the client needs to know about the superseding motion

        $out = [
            'ended' => $motion,
            'superseding' => $superseding
        ];


        //Broadcast to non-chair members
        MotionClosed::dispatch($motion);

        return response()->json($out);

    }


    /**
     * Returns the motion at the top of the pending queue
     * @param Meeting $meeting
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getCurrentMotion(Meeting $meeting)
    {
        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

        $this->authorize('viewAllMeetingMotions', [Motion::class, $meeting]);

        $result = $this->motionStackRepo->getCurrentMotion($meeting);

        return response()->json($result);
    }


    /**
     * @param Meeting $meeting
     * @param Motion $motion
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function setAsCurrentMotion(Meeting $meeting, Motion $motion)
    {
        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

        $this->authorize('setAsCurrent', $motion);

        $result = $this->motionStackRepo->setAsCurrentMotion($meeting, $motion);

        NewCurrentMotionSet::dispatch($motion);
        return response()->json($result);
    }

    /**
     * Allows the motion to be voted upon
     * and tells all listening clients to start voting
     * @param Motion $motion
     */
    public function startVotingOnMotion(Motion $motion){
        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

        $this->authorize('markComplete', $motion);

        $motion->is_voting_allowed = true;
        $motion->save();

        //The motion was probably the current one, but just in case
        $meeting = $motion->meeting;
        $motion = $this->motionStackRepo->setAsCurrentMotion($meeting, $motion);
//        $motion->is_current = true;
//        $motion->save();

        //Send the push message to all clients
        VotingOnMotionOpened::dispatch($motion);

        //return the success message
        response()->json($motion);

    }


    /**
     * Creates or updates the stored map from a map
     * sent by the client
     *
     * dev Possibly deprecated....
     *
     * @param Meeting $meeting
     * @param Request $request
     * @return bool|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function storeOrder(Meeting $meeting, Request $request)
    {
        try {
            $assignmentDao = app()->make(IAssignmentRepository::class);
            $assignmentDao->processIncoming($meeting, $request->input('order'));
            return $this->sendAjaxSuccess();
        } catch (Exception $e) {
            return $this->sendAjaxFailure();
        }
    }


    /**
     * Get the map of motions for the given meeting
     *
     * dev Possibly deprecated....
     *
     * @param Meeting $meeting
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getOrder(Meeting $meeting)
    {
        $this->authorize('viewAllMeetingMotions', $meeting);

        $assignmentRepo = app()->make(IAssignmentRepository::class);

        $out = $assignmentRepo->getMotionOrderForClient($meeting);

        return response()->json($out);

//        $out = ['data' => $meeting->id, 'children' => [], 'parent' => $meeting->id];
//        //Get the meeting with the meeting as the motion id
//        $assignmentTree = Assignment::where(['motion_id', $meeting->id])->get();
//        if ( $assignmentTree->hasChildren() ) {
//            $children = $assignmentTree->getChildren();
//            foreach ( $children as $child ) {
//                //We are receiving child Assignments, but we
//                //want the Motion objects
//
//            }
//        }
        //
    }


}
