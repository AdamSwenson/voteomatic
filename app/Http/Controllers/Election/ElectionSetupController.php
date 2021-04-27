<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Election\Candidate;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use Illuminate\Http\Request;

class ElectionSetupController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('auth');

    }


    public function dev()
    {

        //todo  Note, we'll actually want to usually start from no existing meeting/election

        $election = Meeting::factory()->election()->create();

        $data = ['data' => [
            'meeting' => $election,
            'meeting_id' => $election->id
        ]
        ];


        return view('dev.dev-election-setup', $data);
    }

    /**
     * Returns everyone who could be a candidate for the office.
     *
     * NB, eligibility is sorted out at the office level, not the election
     * level. This allows finer grained distinctions, even if we normally just
     * use the same pool.
     */
    public function getCandidatePool(Motion $motion){

        //todo Returns all members of the meeting or somehow gets from canvas

        $pool = Candidate::factory()->count(10)->create(['motion_id' => $motion->id]);

        return response()->json($pool);

    }


}
