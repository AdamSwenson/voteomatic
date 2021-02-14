<?php

namespace App\Http\Controllers\Election;

use App\Exceptions\DoubleVoteAttempt;
use App\Exceptions\VoteSubmittedAfterMotionClosed;
use App\Http\Controllers\Controller;
use App\Http\Requests\VoteRequest;
use App\Models\Election\Candidate;
use App\Models\Motion;
use App\Models\Vote;
use App\Repositories\IVoterEligibilityRepository;
use Illuminate\Http\Request;

class ElectionVoteController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('auth');
//        $this->middleware('vote-eligibility');

        //   $this->middleware('previously-voted');
//        $this->middleware('motion-closed');

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
            $this->getUser();

            $vote = new Vote;

            //todo Write in candidates

            $candidate = Candidate::find($request->candidate_id);

            $vote->candidate()->associate($candidate);


            //Create a hash stored on vote which only the user
            //will have access to.
//            $vote->makeReceiptHash();

            $motion->votes()->save($vote);
            $motion->save();

//
//
//
//
//
//            //This is already handled by the middleware. It probably should eventually be
//            //removed once there's no chance the middleware will accidentally get turned off.
//            //We need to be extra careful since there can't be a unique index protecting the
//            //vote. Though we could move the creation of the receipt/vote record before the vote and protect
//            //against double receipts. But that will require a bunch of exception checking and
//            //maybe rolling back the addition of the record, so doing this extra layer for now.
//            $this->voterEligibilityRepo = app()->make(IVoterEligibilityRepository::class);
//            if ($this->voterEligibilityRepo->hasAlreadyVoted($motion, $this->user)) {
//                throw new DoubleVoteAttempt;
//            }
//
//            $vote = new Vote;
//
//            if ($request->vote === 'yay') {
//                $vote->is_yay = true;
//            } elseif ($request->vote === 'nay') {
//                $vote->is_yay = false;
//            }
//
//            //if want to record null for abstentions that would go here
//            //after updating client to send an abstention.
//            //obviously, this will happen over my dead body.
//
//            //Create a hash stored on vote which only the user
//            //will have access to.
//            $vote->makeReceiptHash();
//
//            $motion->votes()->save($vote);
//            $motion->save();
//
//            //At this point, the vote itself has been saved.
//            //Now we need to separately record that user has voted
//            $this->voterEligibilityRepo->recordVoted($motion, $this->user);

            return $vote;

        }catch (DoubleVoteAttempt $e){
            abort($e::ERROR_CODE);
        }catch (VoteSubmittedAfterMotionClosed $e2){
            abort($e2::ERROR_CODE);
        }
    }

}
