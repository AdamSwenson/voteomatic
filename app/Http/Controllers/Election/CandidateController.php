<?php

namespace App\Http\Controllers\Election;

use App\Exceptions\BadWriteInAttempt;
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
use Illuminate\Support\Facades\Validator;

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
     * @return array
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
     * Adds an official candidate to the ballot. This can only be done
     * by the owner
     *
     * @param PoolMember $poolMember
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function addCandidateToBallot(PoolMember $poolMember, Request $request)
    {
        $this->setLoggedInUser();

        $motion = $poolMember->motion;
        $person = $poolMember->person;

        //checks that user owns meeting
        $this->authorize('addToBallot', [Candidate::class,  $motion]);

        $candidate = $this->candidateRepo->addCandidateToBallot($motion, $person);

        return response()->json($this->makeCandidateResponse($candidate));
    }

    /**
     * Creates a candidate when a voter writes someone in.
     * NB, validation of the write in name is handled by middleware defined on the route
     *
     * @param Motion $motion
     * @param WriteInCandidateRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function addWriteInCandidate(Motion $motion, WriteInCandidateRequest $request)
    {
        $this->setLoggedInUser();

        //authorizes regular users and owner as long as part of meeting!
        $this->authorize('addWriteInCandidate', [Candidate::class,  $motion]);

        $candidate = $this->candidateRepo->addWriteInCandidate($motion, $request->first_name, $request->last_name, $request->info);

        //Send the candidate to the client
        return response()->json($this->makeCandidateResponse($candidate));
    }


    /**
     * Returns non-write in candidates for office
     *
     * @param Motion $motion
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getOfficialCandidatesForOffice(Motion $motion)
    {
        $this->setLoggedInUser();

        $this->authorize('viewOfficialCandidates', [Candidate::class, $motion]);

        $candidates = $this->candidateRepo->getOfficialCandidatesForOffice($motion);

        $out = [];

        foreach ($candidates as $candidate) {
            $out[] = $this->makeCandidateResponse($candidate);
        }

        return response()->json($out);
    }


    /**
     *
     * This makes the person no longer a candidate for an office
     *
     * @param Candidate $candidate
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function removeCandidate(Candidate $candidate)
    {
        $this->setLoggedInUser();
        $this->authorize('delete', [Candidate::class, $candidate]);
        $candidate->delete();
        return response()->json(200);
    }


//    public function removeCandidateFromBallot(Candidate $candidate)


//    /**
//     * Display the specified resource.
//     *
//     * @param Candidate $candidate
//     * @return \Illuminate\Http\Response
//     */
//    public function show(Candidate $candidate)
//    {
//        $this->setLoggedInUser();
//
//        $this->authorize('view', [Candidate::class, $candidate]);
//
//        return response()->json($this->makeCandidateResponse($candidate));
//
//    }


    //    /**
//     * Store a newly created resource in storage.
//     *
//     * @param \Illuminate\Http\Request $request
//     * @param Motion $motion
//     * @return \Illuminate\Http\Response
//     */
//    public function store(CandidateRequest $request)
//    {
//        $motion = Motion::find($request->motion_id);
//
//        $candidate = $this->electionRepo->addCandidate($motion, $request);
////
//////        $candidate = $this->electionRepo->addCandidate($motion, $request->first_name, $request->last_name, $request->info, $request->is_write_in);
////
////        //NB, id here is the pool member's id
////        if($request->has('id')){
////            //Check if the pool member has been added as a candidate yet
////            $candidate = Candidate::where('pool_member_id', $request->id)
////                ->where('motion_id', $motion->id)
////                ->first();
////        }else{
////            $candidate = $this->electionRepo->addCandidate($motion, $request->first_name, $request->last_name, $request->info, $request->is_write_in);
////        }
//
//
//        return response()->json($this->makeCandidateResponse($candidate));
//
//    }


}
