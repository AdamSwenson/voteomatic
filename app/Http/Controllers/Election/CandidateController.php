<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Motion $office
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Motion $office)
    {
        $candidate = $this->electionRepo->addCandidate($office, $request->name, $request->info);

        return response()->json($candidate);

    }

    public function getCandidatesForOffice(Motion $motion){
        return response()->json($motion->candidates);
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
    public function update(Request $request, Candidate $candidate)
    {
        $d = $request->all();
        $d = $d['data'];
        $candidate->update($d);
        return response()->json($candidate);

    }

    /**
     * Remove the specified resource from storage.
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
