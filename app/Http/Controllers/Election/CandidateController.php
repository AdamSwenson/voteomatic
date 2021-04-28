<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Http\Requests\CandidateCreationRequest;
use App\Models\Election\Candidate;
use App\Models\Motion;
use App\Repositories\Election\IElectionRepository;
use Illuminate\Http\Request;

class CandidateController extends Controller
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
     * @param \Illuminate\Http\Request $request
     * @param Motion $motion
     * @return \Illuminate\Http\Response
     */
    public function store(CandidateCreationRequest $request)
    {
        $motion = Motion::find($request->motion_id);

        if($request->has('id')){
            //If the candidate already exists, we can skip the
            //creation process and send the same object back to the client.
            $candidate = Candidate::where('id', $request->id)
                ->where('motion_id', $motion->id)
                ->first();
        }else{
            $candidate = $this->electionRepo->addCandidate($motion, $request->name, $request->info, $request->is_write_in);
        }


        return response()->json($candidate);

    }

    /**
     * Returns non-write in candidates for office
     *
     * @param Motion $motion
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCandidatesForOffice(Motion $motion){
        return response()->json($motion->candidates()->official()->get());
    }

    /**
     * Display the specified resource.
     *
     * @param Candidate $candidate
     * @return \Illuminate\Http\Response
     */
    public function show(Candidate $candidate)
    {
        return response()->json($candidate);

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
     * @param Candidate $candidate
     * @return \Illuminate\Http\Response
     */
    public function update(Candidate $candidate, Request $request)
    {
        $d = $request->all();
        $candidate->update($d);
        return response()->json($candidate);

    }

    /**
     * Remove the specified resource from storage.
     *
     * This makes the person no longer a candidate for an office
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Candidate $candidate)
    {
        $candidate->delete();
        return response()->json(200);
    }
}
