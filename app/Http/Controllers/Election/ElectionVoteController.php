<?php

namespace App\Http\Controllers\Election;

use App\Exceptions\BallotStuffingAttempt;
use App\Exceptions\DoubleVoteAttempt;
use App\Exceptions\ExcessCandidatesSelected;
use App\Exceptions\VoteSubmittedAfterMotionClosed;
use App\Http\Controllers\Controller;
use App\Http\Requests\ElectionVoteRequest;
use App\Http\Requests\VoteRequest;
use App\Models\Election\Candidate;
use App\Models\Motion;
use App\Models\Vote;
use App\Repositories\Election\IElectionVoteRepository;
use App\Repositories\IVoterEligibilityRepository;
use Illuminate\Http\Request;

class ElectionVoteController extends Controller
{
    /**
     * @var IVoterEligibilityRepository|mixed
     */
    public $voterEligibilityRepo;

    /**
     * @var IElectionVoteRepository|mixed
     */
    public $electionVoteRepo;


    /**
     * Create a new controller instance.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct()
    {

        $this->middleware('auth');
        $this->middleware('check-election-phase');
//        $this->middleware('vote-eligibility');
        $this->middleware('previously-voted');
        $this->middleware('motion-closed');
        $this->middleware('excess-candidates-selected');
        $this->middleware('check-for-ballot-stuffing');

        $this->voterEligibilityRepo = app()->make(IVoterEligibilityRepository::class);
        $this->electionVoteRepo = app()->make(IElectionVoteRepository::class);
    }


    /**
     * Saves the vote to the database and creates a record
     * that the user has voted.
     *
     * @param Motion $motion
     * @param ElectionVoteRequest $request
     * @return Vote|string[]
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function recordVote(ElectionVoteRequest $request, Motion $motion)
    {

        try {
            $this->setLoggedInUser();
            $this->authorize('castVoteForOffice', [Motion::class, $motion]);

            //Record the votes and return a single receipt hash
            $hash = $this->electionVoteRepo->recordOfficeVotes($motion, $request->candidateIds);

            if (isset($hash)) {

                //At this point, the vote itself has been saved.
                //Now we need to separately record that user has voted
                $this->voterEligibilityRepo->recordVoted($motion, $this->user);

                return response()->json(['receipt' => $hash]);

            }
//
//            $candidates = [];
//            foreach ($request->candidateIds as $candidateId) {
//
//                //Look up the object to make sure it exists
//                //We don't just look at the id to avoid id spraying mischief
//                $candidates[] = Candidate::where('motion_id', $office->id)
//                    ->where('id', $candidateId)
//                    ->firstOrFail();
//
//            }
//
////            foreach ($request->writeIns as $name) {
////                $candidates[] = Candidate::create(['name' => $name,
////                    'motion_id' => $motion->id,
////                    'is_write_in' => true,
////                ]);
////            }
//
//
//            //And now record votes
//            $hash = Vote::makeReceiptHash();
//
//            foreach ($candidates as $candidate) {
//                Vote::create([
//                    'motion_id' => $office->id,
//                    'candidate_id' => $candidate->id,
//                    'receipt' => $hash
//                ]);
//            }


            //Create a hash stored on vote which only the user
            //will have access to.
//            $vote->makeReceiptHash();
//
//            $motion->votes()->save($vote);
//            $motion->save();

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

//            return $vote;

        } catch (DoubleVoteAttempt $e) {
            abort($e::ERROR_CODE);
        } catch (VoteSubmittedAfterMotionClosed $e2) {
            abort($e2::ERROR_CODE);
        } catch (ExcessCandidatesSelected $e3) {
            abort($e3::ERROR_CODE);
        }
//        catch (BallotStuffingAttempt $e4){
//            abort($e4::ERROR_CODE);
//        }
    }


}
