<?php


namespace App\Repositories\Election;


use App\Http\Requests\CandidateRequest;
use App\Models\Election\Candidate;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use App\Repositories\IMeetingRepository;
use Hamcrest\Description;
use Illuminate\Support\Facades\Log;
use Tests\helpers\FakeFullElectionMaker;

/**
 * Class ElectionRepository
 *
 * Handles setup and getting parts of an election.
 *
 * Votes and results are handled elsewhere
 *
 * @package App\Repositories\Election
 */
class ElectionRepository implements IElectionRepository
{


//    public function addCandidate(Motion $motion, CandidateRequest $request)
//    {
//
////        //NB, id here is the pool member's id
////        if ($request->has('id')) {
////            //Check if the pool member has been added as a candidate yet
////            $candidate = Candidate::where('pool_member_id', $request->id)
////                ->where('motion_id', $motion->id)
////                ->first();
////
////            if (is_null($candidate)) {
//                $candidate = Candidate::create([
//                    'first_name' => $request->first_name,
//                    'last_name' => $request->last_name,
//                    'info' => $request->info,
//                    'pool_member_id' => $request->id,
//                    'motion_id' => $motion->id
//                ]);
////            }
////        }
//
//
////        $candidate->motion()->associate($motion);
//
////dd($motion);
//        return $candidate;
//    }

    /**
     * @param Meeting $election
     * @param $officeName Name of office which will be the content of the motion
     * @param $description Description of office which will be the description
     * @param $maxWinners
     * @param $type Normally 'election' if an office. If proposition, 'proposition'
     * @return mixed
     */
    public function addOfficeToElection(Meeting $election, $officeName = '', $description = '', $maxWinners = 1, $type = 'election')
    {
        $office = Motion::create([
            'content' => $officeName,
            'description' => $description,
            'meeting_id' => $election->id,
            'max_winners' => $maxWinners,
            //Otherwise it will not be returned when we request the offices
            //via the motion controller
            'seconded' => true,
            'type' => $type
        ]);

        return $office;

    }

    /**
     * Creates a copy of the current election with all the same motions,
     * users, and other information except for votes
     * @param Meeting $meeting
     * @return void
     */
    public function duplicateElection(Meeting $meeting)
    {
        $meetingRepo = app()->make(IMeetingRepository::class);
        $candidateRepo = app()->make(CandidateRepository::class);

        //Copy election
        $newElection = $meetingRepo->createElectionForUser($meeting->getOwner());
        $newElection->name = $meeting->name . " COPY";
        $newElection->info = $meeting->info;

        //dev what to do about lti?
        //Copy users
        foreach ($meeting->users as $user) {
            //createElectionForUser already associated the owner
            if($user->id !== $meeting->getOwner()->id){
                $newElection->addUserToMeeting($user);
                $newElection->save();
            }
        }

        //Copy offices and candidates
        foreach ($meeting->motions as $motion) {
            $office = $this->addOfficeToElection($newElection, $motion->content, $motion->description, $motion->max_winners, $motion->type);

            foreach ($motion->poolMembers as $member) {
                $person = $member->person;
                $newPerson = $person->replicate();
                $newPerson->save();
                $candidateRepo->addPersonToPool($office, $newPerson);
                $candidateRepo->addCandidateToBallot($office, $newPerson);
            }
        }

        $newElection->save();
        Log::info("Created new election id $newElection->id from election id $meeting->id");
        return $newElection;
    }


    public function getCandidates(Motion $motion)
    {

        return $motion->candidates;

    }

//    /**
//     * Returns the candidate(s) with
//     * the highest number of votes
//     *
//     * If the $returnCandidateObjects param is true, it will
//     * return a collection of Candidate objects in descending order
//     * by vote total
//     *
//     * @param Motion $motion
//     * @param bool $returnCandidateObjects
//     * @return \Illuminate\Support\Collection
//     */
//    public function getWinners(Motion $motion, $returnCandidateObjects=false)
//    {
//        $results = $this->getResults($motion);
//        $top = [];
//        $topScore = $results->values()->max();
//
//        if($returnCandidateObjects){
//            $objResults = $this->getResults($motion, $returnCandidateObjects);
//            foreach ($objResults as $result) {
//                if ($result->totalVotesReceived === $topScore) {
//                    $top[] = $result;
//                }
//            }
//        }
//
//        else{
//            foreach ($results as $k => $v) {
//                if ($v === $topScore) {
//                    $top[$k] = $v;
//                }
//            }
//        }
//
//        return collect($top);
//
//    }
//
//
//
//    /**
//     * Returns a collection of [candidate name => vote total]
//     * entries from most to least votes.
//     *
//     * If the $returnCandidateObjects param is true, it will
//     * return a collection of Candidate objects in descending order
//     * by vote total
//     *
//     * @param Motion $motion
//     * @param bool $returnCandidateObjects
//     * @return \Illuminate\Support\Collection
//     */
//    public function getResults(Motion $motion, $returnCandidateObjects=false)
//    {
//
//        $out = [];
//        if($returnCandidateObjects){
//
////            $results = collect($motion->candidates)->sortByDesc('totalVotesReceived');
////foreach($results-)
////
//            return collect($motion->candidates)
//                ->sortByDesc('totalVotesReceived');
//        }
//
//        return collect($motion->candidates)
//            ->sortByDesc('totalVotesReceived')
//            ->pluck('totalVotesReceived', 'name', 'id');
//    }
//
//    /**
//     * Returns a collection of arrays:
//     *     candidate => Candidate object,
//     *     pct_of_total = float
//     *
//     * @param Motion $motion
//     * @return \Illuminate\Support\Collection
//     */
//    public function getVotePercentages(Motion $motion){
////        $results = $this->getResults($motion, true);
////
////        $out = [];
//////dd($results);
////        foreach($results as $result){
////
////            $pct = $motion->totalVotesCast > 0 ? $result->totalVotesReceived / $motion->totalVotesCast : 0;
////
////            $out[] = [
////                'candidateId' => $result->id,
////                'candidateName' => $result->name,
////                'voteCount' => $result->totalVotesReceived,
////                'pct_of_total' => $pct
////            ];
////        }
////
////        return collect($out);
//
//    }


}
