<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motion extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'description', 'requires'];


    const ALLOWED_VOTE_REQUIREMENTS = [0.5, 0.75];


    // ------------------ properties

    public function abstentions()
    {
        return $this->votes()->where('is_yay', null)->get();
    }

    /**
     * Returns array of vote objects which were cast in the affirmative
     * @return mixed
     */
    public function affirmativeVotes()
    {
        return $this->votes()->where('is_yay', true)->get();
    }

    public function negativeVotes()
    {
        return $this->votes()->where('is_yay', false)->get();
    }

    /**
     * The number of votes which the affirmatives must exceed
     * for the motion to pass
     * DO NOT USE >=
     */
    public function getVoteCountThresholdAttribute()
    {
        return $this->totalVotesCast * $this->requires;
    }

    /**
     * Whether the motion has succeeded
     */
    public function getPassedAttribute()
    {
     return count($this->affirmativeVotes) > $this->voteCountThreshold;
    }

    /**
     * Whether the motion requires a majority
     * @return bool
     */
    public function getRequiresMajorityAttribute()
    {
        return $this->requires == 0.5;
    }

    /**
     * Whether the motion requires 2/3
     */
    public function getRequiresTwoThirdsAttribute()
    {
        $this->requires == 0.75;
    }

    /**
     * Returns the total number of votes which have been cast,
     * including abstentions [?!?!]
     * @return int
     */
    public function getTotalVotesCastAttribute()
    {
        return count($this->votes);
    }

    // ------------------ relationships

    /**
     * All users authorized to alter the motion
     * todo Set up pivot table so can have more than one
     */
    public function administrators()
    {
        return $this->belongsToMany(User::class, 'motion_admins')->withTimestamps();
    }


    /**
     * All votes which have been cast on the motion
     */
    public function votes()
    {
        $this->hasMany(Vote::class);
    }


}
