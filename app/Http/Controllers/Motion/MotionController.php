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
    public function __construct(){
        // TODO DEV ENSURE THE TEST HARNESS USER WAS REMOVED BEFORE ANY PRODUCTION USE
        $this->getUser();

    }

//    /**
//     * Display a listing of the resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function index()
//    {
//        //
//    }
//
//    /**
//     * Show the form for creating a new resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function create()
//    {
//        //
//    }

    public function getAllForMeeting(Meeting $meeting)
    {
        return response()->json($meeting->motions()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MotionRequest $request
     * @return Response
     */
    public function store(MotionRequest $request)
    {
//        dd($request->all());
        $motion = Motion::create($request->all());
        return response()->json($motion);
    }

    /**
     * Display the specified resource.
     *
     * @param Motion $motion
     * @return Response
     */
    public function show(Motion $motion)
    {
        return response()->json($motion);

    }

//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param \App\Models\Motion $motion
//     * @return \Illuminate\Http\Response
//     */
//    public function edit(Motion $motion)
//    {
//        //
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Motion $motion
     * @return Response
     */
    public function update(MotionRequest $request, Motion $motion)
    {

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
        //
    }
}
