<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Http\Requests\MotionRequest;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MotionRequest $request, Meeting $election)
    {
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
