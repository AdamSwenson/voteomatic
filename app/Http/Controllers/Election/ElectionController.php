<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;

class ElectionController extends Controller
{

//    const DEV_ELECTION_ID = 85;


    //with votes
    const DEV_ELECTION_ID = 86;

    public function dev()
    {

        $election = Meeting::find(self::DEV_ELECTION_ID);

        $data = ['data' => [
            'meeting' => $election,
            'meeting_id' => $election->id
        ]
        ];


        return view('dev.dev-election', $data);
    }



    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //todo authentication for accessing list of meetings
        return Meeting::where('is_election', true)->all();
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

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->setLoggedInUser();

        //Since we are creating the meeting without
        //the fields filled in, we may have blank meetings
        //in the database. Thus we will try to reuse an existing empty
        //meeting object before actually creating a new one
        $election = $this->user->meetings()
            ->where('name', null)
            ->where('date', null)
            ->where('is_election', true)
            ->first();
//todo Should this be checking that the user is owner? Otherwise a user who is a member in a different meeting could take over ownership (of a blank event)....
        //todo is this enough to ensure that the meeting is empty? maybe a method on meeting would be better?

        if (!is_null($election)) {
            $election->update($request->all());
        } else {
            $election = Meeting::create($request->all());

            //todo Do we always want the creator to be the owner?
            $election->owner_id = $this->user->id;
        }

        $election->is_election = true;
        $election->save();

        $this->user->meetings()->attach($election);
        $this->user->save();

        return response()->json($election);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Meeting $election)
    {

        return response()->json($election);
        //
    }

//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function edit($id)
//    {
//        //
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Meeting $election
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meeting $election)
    {
        $d = $request->all();
        $d = $d['data'];
        $election->update($d);
        return response()->json($election);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Meeting $election
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Meeting $election)
    {

        $election->delete();
        return response()->json(200);
    }
}
