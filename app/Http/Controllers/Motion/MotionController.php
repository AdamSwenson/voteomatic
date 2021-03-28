<?php

namespace App\Http\Controllers\Motion;

use App\Http\Controllers\Controller;
use App\Http\Requests\MotionRequest;
use App\Models\Meeting;
use App\Models\Motion;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MotionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->setLoggedInUser();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAll', Motion::class);

        return Motion::all();
    }


    /**
     * @param Meeting $meeting
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getAllForMeeting(Meeting $meeting)
    {
        $this->authorize('viewAllMeetingMotions', [Motion::class, $meeting]);


        return response()->json($meeting->motions()->get());
    }


    /**
     * Store a newly created resource in storage.
     *
     * This does not have an associated meeting and
     * normally won't be used
     *
     * @param MotionRequest $request
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(MotionRequest $request)
    {
        $this->authorize('create', Motion::class);


        if (!$request->has('meetingId')) {
            //If we decide to allow motions to be created
            //independent of a meeting, we will hit this
            $motion = Motion::create($request->all());

        } else {
            //This is the normal path since we expect an incoming
            //meeting id

            //Since we are creating the motion without
            //the fields filled in, we may have blank motions
            //in the database. Thus we will try to reuse an existing empty
            //motion object before actually creating a new one
            $motion = Motion::where('meeting_id', $request->meetingId)
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

            //Add it to the meeting
            //Note this will be harmlessly redundant if we
            //are using the preexisting object
            $meeting = Meeting::find($request->meetingId);
            $meeting->motions()->save($motion);
        }

        return response()->json($motion);
    }

    /**
     * Display the specified resource.
     *
     * @param Motion $motion
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Motion $motion)
    {
        $this->authorize('view', $motion);

        return response()->json($motion);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Motion $motion
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(MotionRequest $request, Motion $motion)
    {
        $this->authorize('update', $motion);

        //this is necessary because the request object has
        // at the top level:
        //      data : stuff we want,
        //      _method : put
        // in order to make the put request work
        $d = $request->all();
        $d = $d['data'];
        $motion->update($d);
        return response()->json($motion);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Motion $motion
     * @return Response
     */
    public function destroy(Motion $motion)
    {
        $this->authorize('delete', $motion);

        $motion->delete();

        return response()->json(200);
    }
}
