<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Repositories\IMeetingRepository;
use Illuminate\Http\Request;

class ElectionController extends Controller
{

//    const DEV_ELECTION_ID = 85;


    //with votes
    const DEV_ELECTION_ID = 86;
    /**
     * @var IMeetingRepository|mixed
     */
    public $meetingRepo;

//    public function dev()
//    {
//
//        $election = Meeting::find(self::DEV_ELECTION_ID);
//
//        $data = ['data' => [
//            'meeting' => $election,
//            'meeting_id' => $election->id
//        ]
//        ];
//
//
//        return view('dev.dev-election', $data);
//    }


    public function __construct()
    {
        $this->meetingRepo = app()->make(IMeetingRepository::class);
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->setLoggedInUser();
        $this->authorize('viewIndex', Meeting::class);
        return Meeting::where('is_election', true)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->setLoggedInUser();
        $this->authorize('createElection', Meeting::class);

        //todo Do we always want the creator to be the owner?
        //todo Do we always want the creator to be a member?

        //Since we are creating the meeting without
        //the fields filled in, we may have blank meetings
        //in the database. Thus we will try to reuse an existing empty
        //meeting object before actually creating a new one
        $election = $this->meetingRepo->createElectionForUser($this->user);
        $election->update($request->all());
        return response()->json($election);

    }

    /**
     * Display the specified resource.
     *
     * @param Meeting $meeting
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Meeting $meeting)
    {
        $this->setLoggedInUser();
        $this->authorize('viewElection', [Meeting::class, $meeting]);
        return response()->json($meeting);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Meeting $meeting
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Meeting $meeting)
    {
        $this->setLoggedInUser();
        $this->authorize('updateElection', [Meeting::class, $meeting]);
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
     * @throws \Exception
     */
    public function destroy(Meeting $meeting)
    {
        $this->setLoggedInUser();
        $this->authorize('deleteElection', [Meeting::class, $meeting]);
        $meeting->delete();
        return response()->json(200);
    }
}
