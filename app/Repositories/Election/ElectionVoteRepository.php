<?php


namespace App\Repositories\Election;


use App\Exceptions\ExcessCandidatesSelected;
use App\Models\Election\Candidate;
use App\Models\Motion;
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
     * NB, cannot name the motion variable office without messing up the model injection
     *
     * @param Motion $motion
     * @param array $candidateIds
     * @return string
     * @throws ExcessCandidatesSelected
     */
    public function recordOfficeVotes(Motion $motion, $candidateIds = [])
    {
        //Look up the object to make sure it exists
        //We don't just look at the id to avoid id spraying mischief
        $candidates = [];
        foreach ($candidateIds as $candidateId) {
            $candidates[] = Candidate::where('motion_id', $motion->id)
                ->where('id', $candidateId)
                ->firstOrFail();
        }
        //dev What should we do if it does fail?

        //we already checked for excess selections via middleware, but
        //just to be on the safe side.
        if (sizeof($candidates) > $motion->max_winners) {
            throw new ExcessCandidatesSelected();
        }

        //And now record votes
        //We use the same hash for all votes for an office
        $hash = Vote::makeReceiptHash();

        foreach ($candidates as $candidate) {
            $v = Vote::create([
                'motion_id' => $motion->id,
                'candidate_id' => $candidate->id,
                'receipt' => $hash
            ]);
            $v->save();

        }

        return $hash;

    }

}
