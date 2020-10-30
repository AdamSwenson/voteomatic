<?php

namespace App\Http\Controllers;

use App\Exceptions\DoubleVoteAttempt;
use App\Http\Requests\VoteRequest;
use App\Models\Motion;
use App\Models\Vote;
use App\Repositories\IVoterEligibilityRepository;
use App\Repositories\VoterEligibilityRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;




/**
 * Class RecordVoteController
 *
 * Handles validating and recording cast votes
 *
 * @package App\Http\Controllers
 */
class RecordVoteController extends Controller
{

    const DEV_USER_ID = 1;

    /**
     * @var VoterEligibilityRepository
     */
    public VoterEligibilityRepository $voterEligibilityRepo;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->voterEligibilityRepo = app()->make(IVoterEligibilityRepository::class);
//        $this->voterEligibilityRepo = new VoterEligibilityRepository();

        $this->middleware('auth');
//        $this->middleware('vote-eligibility');

//
//        // TODO DEV REMOVE BEFORE ANY PRODUCTION USE
        $env = env('APP_ENV');
        if($env != 'production'){

            Auth::loginUsingId(self::DEV_USER_ID);
        }
        $this->user = Auth::user();

    }


    /**
     * Saves the vote to the database and creates a record
     * that the user has voted.
     *
     * @param Motion $motion
     * @param VoteRequest $request
     * @return Vote|string[]
     */
    public function recordVote(Motion $motion, VoteRequest $request){
        try {
            if ($this->voterEligibilityRepo->hasAlreadyVoted($motion, $this->user)) {

                // TODO DEV REMOVE BEFORE ANY PRODUCTION USE
                throw new DoubleVoteAttempt;
            }

            $vote = new Vote;


            if ($request->vote === 'yay') {
                $vote->is_yay = true;
            } elseif ($request->vote === 'nay') {
                $vote->is_yay = false;
            }

            //if want to record null for abstentions that would go here
            //after updating client to send an abstention

            //Create a hash stored on vote which only the user
            //will have access to.
            $vote->makeReceiptHash();

            $motion->votes()->save($vote);
            $motion->save();

            //todo record that user has voted
            $this->voterEligibilityRepo->recordVoted($motion, $this->user);


            return $vote;

        }catch (DoubleVoteAttempt $e){
            abort($e::ERROR_CODE);
//            return ['error' => "Previously voted"];
//            print($e);
        }
    }
}
