<?php

namespace App\Http\Controllers\Motion;

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

    public function __construct()
    {

        $this->middleware('auth');

        $this->motionStackRepo = app()->make(IMotionStackRepository::class);
        $this->motionRepo = app()->make(IMotionRepository::class);
    }


    public function markMotionComplete(Motion $motion)
    {

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

        return response()->json($out);

    }



    /**
     * Returns the motion at the top of the pending queue
     * @param Meeting $meeting
     */
    public function getCurrentMotion(Meeting $meeting)
    {
        $result = $this->motionStackRepo->getCurrentMotion($meeting);
        return response()->json($result);
    }


    public function setAsCurrentMotion(Meeting $meeting, Motion $motion)
    {
        $result = $this->motionStackRepo->setAsCurrentMotion($meeting, $motion);
        return response()->json($result);
    }


    /**
     * Creates or updates the stored map from a map
     * sent by the client
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
     * @param Meeting $meeting
     * @return void
     */
    public function getOrder(Meeting $meeting)
    {
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
