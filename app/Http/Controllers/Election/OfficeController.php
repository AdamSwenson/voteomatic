<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Http\Requests\MotionRequest;
use App\Http\Requests\OfficeCreationRequest;
use App\Models\Meeting;
use App\Models\Motion;
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
     * Remove the specified resource from storage.
     *
     * @param Motion $motion
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Motion $motion)
    {
        $this->authorize('deleteOffice', [Motion::class, $motion]);
        $motion->delete();
        return response()->json(200);
    }


    /**
     * Display the specified resource.
     *
     * @param Motion $motion
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Motion $motion)
    {
        $this->authorize('viewOffice', [Motion::class, $motion]);

        return response()->json($motion);
    }


    /**
     * Create an office and associate it with the election
     * provided in the request.
     *
     * Returns the office (motion) object
     *
     * @param OfficeCreationRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(OfficeCreationRequest $request)
    {
        $this->setLoggedInUser();
        $election = Meeting::find($request->meetingId);
        $this->authorize('createOffice', [Motion::class, $election]);
        $office = $this->electionRepo->addOfficeToElection($election, $request['content'], $request->description, 1);
        return response()->json($office);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Motion $motion
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Motion $motion)
    {
        $this->setLoggedInUser();
        $this->authorize('updateOffice', [Motion::class, $motion]);
        $motion->update($request->all());
        return response()->json($motion);
    }

}
