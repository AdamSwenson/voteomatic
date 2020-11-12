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
//        // TODO DEV REMOVE BEFORE ANY PRODUCTION USE
//        $env = env('APP_ENV');
//        if ($env != 'production') {
//            //this is here in case I am dumb. it is not an excuse to be dumb
//            //and fail to remove before production.
//            Auth::loginUsingId(1, true);
//        }else {
//

            $this->middleware('auth');
//        }
//        $this->user = Auth::user();
    }


    public function getPreviouslyCastVotes(Meeting $meeting)
    {
        $this->getUser();

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
