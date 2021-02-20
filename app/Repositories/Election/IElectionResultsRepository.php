<?php

namespace App\Repositories\Election;

use App\Models\Motion;

interface IElectionResultsRepository
{
    /**
     * Returns a collection with the expected keys etc that the client is expecting
     *
     * @param Motion $motion
     * @param bool $returnCandidateObjects
     * @return \Illuminate\Support\Collection
     */
    public function getResultsForClient(Motion $motion);
}
