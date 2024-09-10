<?php


namespace App\Repositories\Election;


use App\Models\Motion;
use App\Repositories\Election\Calculators\ResultsCalculatorFactory;

class ElectionResultsRepository implements IElectionResultsRepository
{

    public function getPropositionResultsForClient(Motion $motion)
    {
//        $calculator = ResultsCalculatorFactory::make($motion);

        $out = [[
                'motionId' => $motion->id,
                'resultId' => $motion->id,
                'candidateName' => $motion->info['name'],
                'voteCount' => sizeof($motion->affirmativeVotes),
                'pctOfTotal' => sizeof($motion->affirmativeVotes) / $motion->totalVotesCast,
                'isWinner' => $motion->passed,
                'isRunoffParticipant' => false
            ]];

        return collect($out);
    }


    /**
     * Returns a collection with the expected keys etc that the client is expecting
     *
     * @param Motion $motion
     * @param bool $returnCandidateObjects
     * @return \Illuminate\Support\Collection
     */
    public function getResultsForClient(Motion $motion)
    {
        $calculator = ResultsCalculatorFactory::make($motion);

        $out = [];

        foreach ($calculator->results as $candidate) {

            $out[] = [
                'motionId' => $motion->id,
                'candidateId' => $candidate->id,
                'candidateName' => $candidate->name,
                'voteCount' => $candidate->totalVotesReceived,
                'pctOfTotal' => $candidate->getShareOfVotesCast(),
                'isWinner' => $calculator->isWinner($candidate),
                'isRunoffParticipant' => $calculator->isRunoffParticipant($candidate),
                'person' => $candidate->person

            ];
        }


        return collect($out);

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
