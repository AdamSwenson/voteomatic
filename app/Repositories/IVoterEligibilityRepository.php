<?php

namespace App\Repositories;


use App\Exceptions\DoubleVoteAttempt;
use App\Models\Motion;
use App\Models\User;

/**
 * Class VoterEligibilityRepository
 * Handles determinations of whether someone has already voted
 * @package App\Repositories
 */
interface IVoterEligibilityRepository
{


    /**
     * Runs all checks on whether the user is allowed to cast
     * a vote.
     *
     * This is the main method that should be called
     *
     * @param Motion $motion
     * @param User $user
     */
    public function isEligible(Motion $motion, User $user);

    /**
     * Returns true if a record exists for them voting
     * on this motion
     *
     * @param Motion $motion
     * @param User $user
     * @return bool
     */
    public function hasAlreadyVoted(Motion $motion, User $user);

    public function recordVoted(Motion $motion, User $user);
}
