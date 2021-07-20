<?php


namespace App\Repositories\Election;


use App\Exceptions\ExcessCandidatesSelected;
use App\Models\Election\Candidate;
use App\Models\Vote;

class ElectionVoteRepository implements IElectionVoteRepository
{

    /**
     * Given a set of candidate ids, records 1 vote for each
     * candidate and returns a receipt hash.
     *
     *
     * THIS DOES NOT RECORD A RECORD THAT THE USER HAS VOTED. THAT MUST
     * BE HANDLED SEPARATELY
     *
     * @param Motion $office
     * @param array $candidateIds
     * @return string
     * @throws ExcessCandidatesSelected
     */
    public function recordOfficeVotes(Motion $office, $candidateIds = [])
    {
        //Look up the object to make sure it exists
        //We don't just look at the id to avoid id spraying mischief
        $candidates = [];
        foreach ($candidateIds as $candidateId) {
            $candidates[] = Candidate::where('motion_id', $office->id)
                ->where('id', $candidateId)
                ->firstOrFail();
        }
        //dev What should we do if it does fail?

        //we already checked for excess selections via middleware, but
        //just to be on the safe side.
        if (sizeof($candidates) > $office->max_winners) {
            throw new ExcessCandidatesSelected();
        }

        //And now record votes
        //We use the same hash for all votes for an office
        $hash = Vote::makeReceiptHash();

        foreach ($candidates as $candidate) {
            Vote::create([
                'motion_id' => $office->id,
                'candidate_id' => $candidate->id,
                'receipt' => $hash
            ]);
        }

        return $hash;

    }

}
