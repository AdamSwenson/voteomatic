<?php


namespace App\Repositories\Vote;

/**
 * Class VoteTotalRepository
 *
 * Handles all calculations of votes cast for a motion
 *
 * @package App\Repositories\Vote
 */
class VoteTotalRepository
{

    /**
     * @var Motion
     */
    public $motion;

    public function __construct(Motion $motion)
    {


        $this->motion = $motion;
    }

    public function castVotes(){
        return $this->motion->votes()->all();
    }

}