<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\RecordedVoteRecord;
use App\Repositories\IVoterEligibilityRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteHistoryController extends Controller
{
    //

    protected $user;

    public function __construct()
    {

        $this->middleware('auth');

    }


    public function getPreviouslyCastVotes(Meeting $meeting)
    {
        $this->setLoggedInUser();

        $out = [];

        foreach ($meeting->motions as $motion) {
            $rec = RecordedVoteRecord::where('user_id', $this->user->id)
                ->where('motion_id', $motion->id)
                ->first();

            if (!is_null($rec)) {
                //if returned a value, send the motion (not the record)
                //to the client
                $out[] = $motion;
            }

        }


        return response()->json($out);

    }
}
