<?php


namespace App\Repositories\Election;


use App\Models\Election\Candidate;
use App\Models\Meeting;
use App\Models\Motion;

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


    public function addCandidate(Motion $motion, $name = '', $info = '', $isWriteIn = false)
    {
        $candidate = Candidate::create([
            'name' => $name,
            'info' => $info,
            'is_write_in' => $isWriteIn,
            'motion_id' => $motion->id
        ]);

//        $candidate->motion()->associate($motion);

        $candidate->save();
//dd($motion);
        return $candidate;
    }


    public function addOfficeToElection(Meeting $election, $officeName = '', $description = '')
    {
        $office = Motion::create([
            'content' => $officeName,
            'description' => $description,
            'meeting_id' => $election->id
        ]);

        return $office;

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