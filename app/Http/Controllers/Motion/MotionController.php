<?php

namespace App\Http\Controllers\Motion;

use App\Events\MotionSeconded;
use App\Events\MotionNeedingApproval;
use App\Events\NotifyPageRefreshNeeded;
use App\Http\Controllers\Controller;
use App\Http\Requests\MotionRequest;
use App\Models\Meeting;
use App\Models\Motion;
use App\Repositories\IMotionRepository;
use App\Repositories\IMotionStackRepository;
use App\Repositories\MotionRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MotionController extends Controller
{
    /**
     * @var IMotionRepository|mixed
     */
    public $motionRepo;
    public $meeting;

    public function __construct()
    {
        $this->middleware('auth');
        
        //[Added in VOT-104] Checks the settings and determines whether
        // the user is allowed to send a post request
        $this->middleware('motion-make-eligibility');

        $this->motionRepo = app()->make(IMotionRepository::class);
        $this->motionStackRepo = app()->make(IMotionStackRepository::class);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

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

        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

        $this->authorize('viewAllMeetingMotions', [Motion::class, $meeting]);

        //We only want the motions that received a second.
        //If there's ever a need to see what wasn't seconded, that can be done elsewhere
        $motions = $meeting->motions()->where('seconded', true)->get();
        return response()->json($motions);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param MotionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException|\App\Exceptions\IneligibleSecondAttempt
     */
    public function store(MotionRequest $request)
    {
        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

        $this->authorize('create', Motion::class);

        if (!$request->has('meetingId')) {
            //If we decide to allow motions to be created
            //independent of a meeting, we will hit this
            $motion = Motion::create($request->all());

        } else {
            //This is the normal path since we expect an incoming
            //meeting id
            $this->meeting = Meeting::find($request->meetingId);

            //Creates the motion object and sets the user as its author
            $motion = $this->motionRepo->createMotion($this->user, $this->meeting, $request);

            //If the user creating the motion was the chair
            //we can assume it is both in order and not needing a
            //second (presumably they were recording what was said verbally)
            if ($this->meeting->isOwner($this->user)) {
                $this->motionRepo->secondMotion($motion, $this->user);
                $this->motionRepo->markInOrder($motion, $this->user);
                //Tell the client to switch to this motion
                MotionSeconded::dispatch($motion);
                //Update it as the current motion on server
                $motion = $this->motionStackRepo->setAsCurrentMotion($this->meeting, $motion);
//                return redirect()->action([MotionStackController::class, 'setAsCurrentMotion', ['meeting' => $this->meeting, 'motion'=> $motion]]);
            } else {
                // A regular user created it so we will need to
                //seek authorization and (later) a second
                MotionNeedingApproval::dispatch($motion);
            }
        }

        //Check in case we've sent a payload which exceeds pusher's limits
        if(! MotionRepository::isPusherCompatible($motion)){
            NotifyPageRefreshNeeded::dispatch($this->meeting);
        }

        return response()->json($motion);

//            //Since we are creating the motion without
//            //the fields filled in, we may have blank motions
//            //in the database. Thus we will try to reuse an existing empty
//            //motion object before actually creating a new one
//            $motion = Motion::where('meeting_id', $request->meetingId)
//                ->where('content', null)
//                ->where('description', null)
////                ->where('requires', null)
//                ->where('type', null)
//                ->where('is_complete', false)
//                ->first();
//
//            if (!is_null($motion)) {
//                //if we found a motion that was empty,
//                //take whatever data we've been sent and set it
//                //on the motion
//                $motion->update($request->all());
//                $motion->save();
//            } else {
//                //No preexisting empty motion associated with the meeting
//                //has been found
//                $motion = Motion::create($request->all());
//            }
//
//            //Add it to the meeting
//            //Note this will be harmlessly redundant if we
//            //are using the preexisting object
//            $meeting = Meeting::find($request->meetingId);
//            $meeting->motions()->save($motion);


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
        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

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
        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

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
        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

        $this->authorize('delete', $motion);

        $motion->delete();

        return response()->json(200);
    }
}
