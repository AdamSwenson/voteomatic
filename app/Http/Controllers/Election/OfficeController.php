<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Http\Requests\MotionRequest;
use App\Http\Requests\OfficeCreationRequest;
use App\Models\Meeting;
use App\Repositories\Election\IElectionRepository;
use Illuminate\Http\Request;

class OfficeController extends Controller
{

    /**
     * @var IElectionRepository|mixed
     */
    public $electionRepo;

    public function __construct()
    {
        $this->middleware('auth');

        $this->electionRepo = app()->make(IElectionRepository::class);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * Create an office and associate it with the election
     * provided in the request.
     *
     * Returns the office (motion) object
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OfficeCreationRequest $request)
    {

        $election = Meeting::find($request->meetingId);

//        dd($request);
//        $election = $request->getElection();
//        dd($election);
        $office = $this->electionRepo->addOfficeToElection($election, $request['content'], $request->description);
        return response()->json($office);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Motion $office)
    {

        return response()->json($office);
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
