<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Http\Requests\Election\CandidateRequest;
use App\Http\Requests\Election\WriteInCandidateRequest;
use App\Models\Election\Candidate;
use App\Models\Election\Person;
use App\Models\Election\PoolMember;
use App\Models\Motion;
use App\Repositories\Election\ICandidateRepository;
use App\Repositories\Election\IElectionRepository;
use Illuminate\Http\Request;

class CandidateController extends Controller
{

    /**
     * @var IElectionRepository|mixed
     */
    public $electionRepo;
    /**
     * @var ICandidateRepository|mixed
     */
    public $candidateRepo;

    public function __construct()
    {
        $this->middleware('auth');
        $this->candidateRepo = app()->make(ICandidateRepository::class);
        $this->electionRepo = app()->make(IElectionRepository::class);
    }



    /**
     * Handles providing the client with the data it expects
     * when it asks for a candidate
     * @param Candidate $candidate
     */
    public function makeCandidateResponse(Candidate $candidate)
    {
        return [
            'id' => $candidate->id,
            'first_name' => $candidate->person->first_name,
            'last_name' => $candidate->person->last_name,
            'info' => $candidate->person->info,
            'motion_id' => $candidate->motion->id,
            'person_id' => $candidate->person->id,
            'is_write_in' => $candidate->is_write_in
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Motion $motion
     * @return \Illuminate\Http\Response
     */
    public function store(CandidateRequest $request)
    {
        $motion = Motion::find($request->motion_id);

        $candidate = $this->electionRepo->addCandidate($motion, $request);
//
////        $candidate = $this->electionRepo->addCandidate($motion, $request->first_name, $request->last_name, $request->info, $request->is_write_in);
//
//        //NB, id here is the pool member's id
//        if($request->has('id')){
//            //Check if the pool member has been added as a candidate yet
//            $candidate = Candidate::where('pool_member_id', $request->id)
//                ->where('motion_id', $motion->id)
//                ->first();
//        }else{
//            $candidate = $this->electionRepo->addCandidate($motion, $request->first_name, $request->last_name, $request->info, $request->is_write_in);
//        }


        return response()->json($this->makeCandidateResponse($candidate));

    }


    /**
     * @param Motion $motion
     * @param Person $person
     * @param $request
     */
    public function addCandidateToBallot(PoolMember $poolMember, Request $request)
    {
        $motion = $poolMember->motion;
        $person = $poolMember->person;

        $candidate = $this->candidateRepo->addCandidateToBallot($motion, $person);

        return response()->json($this->makeCandidateResponse($candidate));
    }

    public function addWriteInCandidate(Motion $motion, WriteInCandidateRequest $request){
        //authorize regular user!

        //Check whether the write in candidate duplicates an existing candidate
        $possibleDuplicates = Person::where('first_name', $request->first_name)
            ->where('last_name', $request->last_name)
            ->get();

        if(sizeof($possibleDuplicates) > 0){
            $currentCandidates = [];
        }


        //First create a person
        $person = Person::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'info' => $request->info,
        ]);

        //Now make them a candidate
        $candidate = Candidate::create([
            'motion_id' => $motion->id,
            'person_id' => $person->id,
            'is_write_in' => true
        ]);

        //Send the candidate to the client
        return response()->json($this->makeCandidateResponse($candidate));

    }


    /**
     * Returns non-write in candidates for office
     *
     * @param Motion $motion
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOfficialCandidatesForOffice(Motion $motion)
    {
        $candidates = $this->candidateRepo->getOfficialCandidatesForOffice($motion);

        $out = [];

        foreach($candidates as $candidate){
            $out[] = $this->makeCandidateResponse($candidate);
        }

        return response()->json($out);


//        return response()->json($motion->candidates()->official()->get());
    }


//    public function removeCandidateFromBallot(Candidate $candidate)


    /**
     * Display the specified resource.
     *
     * @param Candidate $candidate
     * @return \Illuminate\Http\Response
     */
    public function show(Candidate $candidate)
    {
        return response()->json($this->makeCandidateResponse($candidate));

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

//    /**
//     * Update the specified resource in storage.
//     *
//     * @param \Illuminate\Http\Request $request
//     * @param Candidate $candidate
//     * @return \Illuminate\Http\Response
//     */
//    public function update(Candidate $candidate, CandidateRequest $request)
//    {
//        $d = $request->all();
//        $candidate->update($d);
//        return response()->json($this->makeCandidateResponse($candidate));
//
//    }

    /**
     *
     * This makes the person no longer a candidate for an office
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function removeCandidate(Candidate $candidate)
    {
        $candidate->delete();
        return response()->json(200);
    }
}
