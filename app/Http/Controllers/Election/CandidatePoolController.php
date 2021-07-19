<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Election\Candidate;
use App\Models\Election\Person;
use App\Models\Election\PoolMember;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use App\Repositories\Election\ICandidateRepository;
use Illuminate\Http\Request;

class CandidatePoolController extends Controller
{
    /**
     * @var ICandidateRepository|mixed
     */
    public $candidateRepo;


    /**
     * Create a new controller instance.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct()
    {
        $this->candidateRepo = app()->make(ICandidateRepository::class);
        $this->middleware('auth');
    }

    /**
     * Handles providing the client with the data it expects
     * when it asks for a candidate pool member
     * @param PoolMember $poolMember
     * @return array
     */
    public function makePoolMemberResponse(PoolMember $poolMember)
    {
        return [
            'id' => $poolMember->id,
            'first_name' => $poolMember->person->first_name,
            'last_name' => $poolMember->person->last_name,
            'info' => $poolMember->person->info,
            'motion_id' => $poolMember->motion->id,
            'person_id' => $poolMember->person->id
        ];
    }


    /**
     * Adds a person to the candidate pool for a given office
     * The user must be the meeting's owner to do this.
     *
     * @param Motion $motion
     * @param Person $person
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function addPersonToPool(Motion $motion, Person $person)
    {
        $this->setLoggedInUser();
        $this->authorize('createPoolMember', [PoolMember::class, $motion]);
        $poolMember = $this->candidateRepo->addPersonToPool($motion, $person);
        return response()->json($this->makePoolMemberResponse($poolMember));
    }

    /**
     * Returns everyone who could be a candidate for the office.
     *
     * This can be used by an owner who is not a member of the meeting, since
     * members of meetings are voters. E.g., if the office admin person is the owner
     * of the meeting, they can set up the pool.
     *
     * NB, eligibility is sorted out at the office level, not the election
     * level. This allows finer grained distinctions, even if we normally just
     * use the same pool.
     */
    public function getCandidatePool(Motion $motion)
    {
        $this->setLoggedInUser();
        $this->authorize('viewCandidatePool', [PoolMember::class, $motion]);

        //        $pool = PoolMember::factory()->count(10)->create(['motion_id' => $motion->id]);

        $pool = $motion->poolMembers()->get();

        $out = [];
        foreach ($pool as $m) {
            $out[] = $this->makePoolMemberResponse($m);
        }
        return response()->json($out);

    }


//    public function dev()
//    {
//
//        //todo  Note, we'll actually want to usually start from no existing meeting/election
//
//        $election = Meeting::factory()->election()->create();
//
//        $data = ['data' => [
//            'meeting' => $election,
//            'meeting_id' => $election->id
//        ]
//        ];
//
//
//        return view('dev.dev-election-setup', $data);
//    }

}
