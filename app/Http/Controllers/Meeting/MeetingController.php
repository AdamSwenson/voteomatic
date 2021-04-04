<?php

namespace App\Http\Controllers\Meeting;

use App\Http\Controllers\Controller;
use App\Http\Requests\MeetingRequest;
use App\Models\Meeting;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
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
        $this->authorize('create', Meeting::class);

        //Since we are creating the meeting without
        //the fields filled in, we may have blank meetings
        //in the database. Thus we will try to reuse an existing empty
        //meeting object before actually creating a new one
        $meeting = $this->user->meetings()
            ->where('name', null)
            ->where('date', null)
//            ->where('owner_id', $this->user->id)
            ->first();

        if (!is_null($meeting)) {
            $meeting->update($request->all());
            $meeting->owner_id = $this->user->id;
            $meeting->save();
        } else {
            $meeting = Meeting::create($request->all());
            $meeting->owner_id = $this->user->id;
            $this->user->meetings()->attach($meeting);
            $this->user->save();
        }
        $env = env('APP_ENV');

//dd($meeting);
        return response()->json($meeting);
    }

    /**
     * Display the specified resource.
     *
     * @param MeetingRequest $meeting
     * @return MeetingRequest
     */
    public function show(Meeting $meeting)
    {
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
        $this->authorize('delete', $meeting);

        $meeting->delete();

        //todo Votes and motions also delete

        return response()->json(200);
    }
}
