<?php

namespace App\Repositories\Election;

use App\Models\Motion;

interface IElectionRepository
{
    public function addCandidate(Motion $motion, $name = '', $info = '', $isWriteIn=false);

    public function getCandidates(Motion $motion);

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
    public function getWinners(Motion $motion, $returnCandidateObjects = false);

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
    public function getResults(Motion $motion, $returnCandidateObjects = false);

    /**
     * Returns a collection of arrays:
     *     candidate => Candidate object,
     *     pct_of_total = float
     *
     * @param Motion $motion
     * @return \Illuminate\Support\Collection
     */
    public function getVotePercentages(Motion $motion);
}
