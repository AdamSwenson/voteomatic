<?php

namespace App\Http\Controllers\Meeting;

use App\Http\Controllers\Controller;
use App\Http\Requests\MeetingRequest;
use App\Models\Meeting;
use App\Repositories\IMeetingRepository;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{

    /**
     * @var IMeetingRepository|mixed
     */
    public $meetingRepo;

    public function __construct()
    {
        $this->middleware('auth');

        $this->meetingRepo = app()->make(IMeetingRepository::class);
    }


    /**
     * Returns all meetings associated with the user
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

        $this->authorize('viewIndex', Meeting::class);
        $meetings = $this->user->meetings()->get();
        return response()->json($meetings);
    }

//    /**
//     * Show the form for creating a new resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function create()
//    {
//        //
//    }
//
    /**
     * Create and store a new meeting with the logged in user
     * as owner
     *
     * @param MeetingRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(MeetingRequest $request)
    {
        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

        $this->authorize('create', Meeting::class);

        $meeting = $this->meetingRepo->createMeetingForUser($this->user);

        $meeting->update($request->all());

        return response()->json($meeting);

//        //dev This potentially allows me to fill in a meeting of which you were previously the owner. Is that a problem...
//
//        //Since we are creating the meeting without
//        //the fields filled in, we may have blank meetings
//        //in the database. Thus we will try to reuse an existing empty
//        //meeting object before actually creating a new one
//        $meeting = $this->user->meetings()
//            ->where('name', null)
//            ->where('date', null)
//            ->first();
//
//        if (!is_null($meeting)) {
//            $meeting->update($request->all());
//
//        } else {
//            $meeting = Meeting::create($request->all());
//        }
//
//        $meeting->addUserToMeeting($this->user);
//        $meeting->setOwner($this->user);
//
//        return response()->json($meeting);
    }

    /**
     * Display the specified resource.
     *
     * @param MeetingRequest $meeting
     * @return MeetingRequest
     */
    public function show(Meeting $meeting)
    {
        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

        $this->authorize('view', $meeting);

        return response()->json($meeting);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Meeting $meeting
     * @param MeetingRequest $request
     * @return void
     */
    public function update(Meeting $meeting, MeetingRequest $request)
    {
        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

        $this->authorize('update', $meeting);

        //this is necessary because the request object has
        // at the top level:
        //      data : stuff we want,
        //      _method : put
        // in order to make the put request work
        $d = $request->all();
        $d = $d['data'];
        $meeting->update($d);
        return response()->json($meeting);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Meeting $meeting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meeting $meeting)
    {
        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

        $this->authorize('delete', $meeting);

        $meeting->delete();

        //todo Votes and motions also delete

        return response()->json(200);
    }
}
