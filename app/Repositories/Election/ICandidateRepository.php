<?php

namespace App\Repositories\Election;

use App\Http\Requests\CandidateRequest;
use App\Models\Election\Candidate;
use App\Models\Election\PoolMember;
use App\Models\Motion;

interface ICandidateRepository
{
//    /**
//     * @param string $first_name
//     * @param string $last_name
//     * @param null|array $info
//     */
//    public function createPerson($first_name = '', $last_name = '', $info = null);
//
//    public function addPersonToPool(Motion $motion, Person $person);
//
//    /**
//     * Makes a person into a nominee for a given office
//     *
//     * @param Motion $motion
//     * @param CandidateRequest $request
//     * @return mixed
//     */
//    public function addCandidateToBallot(Motion $motion, Person $person, $is_write_in = null);
//
//    /**
//     * Get all official candidates for the office
//     * in the format the client expects
//     *
//     * @param Motion $motion
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function getOfficialCandidatesForOffice(Motion $motion);
//
//    /**
//     * Handles providing the client with the data it expects
//     * when it asks for a candidate
//     * @param Candidate $candidate
//     */
//    public function makeCandidateResponse(Candidate $candidate);
//
//    public function removeCandidateFromBallot(Candidate $candidate);
//
//    public function removeCandidateFromPool(PoolMember $poolMember);
}
