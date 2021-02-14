<?php


namespace App\Repositories\Election;


use App\Models\Election\Candidate;
use App\Models\Motion;

class ElectionRepository implements IElectionRepository
{


    public function addCandidate(Motion $motion, $name = '', $info = '')
    {

        $candidate = Candidate::create([
            'name' => $name,
            'info' => $info
        ]);

        $candidate->motion()->associate($motion);

        return $candidate;
    }


    public function addOfficeToElection(Meeting $election, $officeName='', $description=''){
        $office = Motion::create([
            'content' => $officeName,
        'description' => $description]);

        $election->motion()->associate($office);

        return $election;

    }


    public function getCandidates(Motion $motion)
    {

        return $motion->candidates;

    }

    /**
     * Returns the candidate(s) with
     * the highest number of votes
     *
     * If the $returnCandidateObjects param is true, it will
     * return a collection of Candidate objects in descending order
     * by vote total
     *
     * @param Motion $motion
     * @param bool $returnCandidateObjects
     * @return \Illuminate\Support\Collection
     */
    public function getWinners(Motion $motion, $returnCandidateObjects=false)
    {
        $results = $this->getResults($motion);
        $top = [];
        $topScore = $results->values()->max();

        if($returnCandidateObjects){
            $objResults = $this->getResults($motion, $returnCandidateObjects);
            foreach ($objResults as $result) {
                if ($result->totalVotesReceived === $topScore) {
                    $top[] = $result;
                }
            }
        }

        else{
            foreach ($results as $k => $v) {
                if ($v === $topScore) {
                    $top[$k] = $v;
                }
            }
        }

        return collect($top);

    }



    /**
     * Returns a collection of [candidate name => vote total]
     * entries from most to least votes.
     *
     * If the $returnCandidateObjects param is true, it will
     * return a collection of Candidate objects in descending order
     * by vote total
     *
     * @param Motion $motion
     * @param bool $returnCandidateObjects
     * @return \Illuminate\Support\Collection
     */
    public function getResults(Motion $motion, $returnCandidateObjects=false)
    {
        if($returnCandidateObjects){
            return collect($motion->candidates)
                ->sortByDesc('totalVotesReceived');
        }

        return collect($motion->candidates)
            ->sortByDesc('totalVotesReceived')
            ->pluck('totalVotesReceived', 'name');
    }

    /**
     * Returns a collection of arrays:
     *     candidate => Candidate object,
     *     pct_of_total = float
     *
     * @param Motion $motion
     * @return \Illuminate\Support\Collection
     */
    public function getVotePercentages(Motion $motion){
        $results = $this->getResults($motion, true);

        $out = [];

        foreach($results as $result){
            $out[] = [
                'candidate' => $result,
                'pct_of_total' =>  $result->totalVotesReceived / $motion->totalVotesCast
            ];
        }

        return collect($out);

    }


}
