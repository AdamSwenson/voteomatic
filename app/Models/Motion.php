<?php

namespace App\Models;

use App\Models\Assignment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motion extends Model
{
    use HasFactory;

    protected $fillable = [
        'applies_to',
        'content',
        'description',
        'is_complete',
        'is_current',
        'meeting_id',
        'requires',
        'type'];

    protected $motionTypes = ['main', 'privileged', 'amendment', 'procedural-main', 'procedural-subsidiary', 'incidental'];

    protected $casts = ['is_complete' => 'boolean', 'is_current' => 'boolean'];

    const ALLOWED_VOTE_REQUIREMENTS = [0.5, 0.66];


    // ------------------ Motion tree related

    /**
     * Add a subsidiary motion to this motion.
     * E.g,, if this is a main motion, the motion to amend would be
     * the subsidiary.
     * If this is an amendment, the second order amendment
     * would be the subsidiary.
     *
     * Procedural motions such as the motion to table or the previous
     * question should also be added through this method.
     *
     * @param Motion $subsidiaryMotion
     */
    public function addSubsidiaryMotion( Motion $subsidiaryMotion){
        $subsidiaryMotion->applies_to = $this->id;
        $subsidiaryMotion->save();
    }

    /**
     * Returns all subsidiary direct descendent motions, FILO ordered
     */
    public function getSubsidiaryMotions(){
        return Motion::where('applies_to', $this->id)->orderBy('id', 'desc')->get(); //->sortDesc();
    }

    /**
     * Returns true if the motion is not subsidiary to any
     * other motion
     * NB, includes incidental motions
     */
    public function isMainMotion(){
        return is_null($this->applies_to);
    }


    // ------------------ properties

    public function abstentions()
    {
        return $this->votes()->where('is_yay', null)->get();
    }

    /**
     * Returns array of vote objects which were cast in the affirmative
     * @return mixed
     */
    public function getAffirmativeVotesAttribute()
    {
        return $this->votes()->where('is_yay', true)->get();
    }

    public function getNegativeVotesAttribute()
    {
        return $this->votes()->where('is_yay', false)->get();
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
        return $this->requires == 0.66;
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

    /**
     * The number of votes which the affirmatives must exceed
     * for the motion to pass
     * todo Should this round up? Is there any case where that matters?
     * DO NOT USE >=
     */
    public function getVoteCountThresholdAttribute()
    {
        return $this->totalVotesCast * $this->requires;

//        return $this->attributes['totalVotesCast'] * $this->requires;
    }


    // ------------------ relationships


    /**
     * All assignments of a task to an activity
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignment()
    {
        return $this->hasOne(Assignment::class);
    }


    /**
     * All users authorized to alter the motion
     * todo Set up pivot table so can have more than one
     */
    public function administrators()
    {
        return $this->belongsToMany(User::class, 'motion_admins')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recordedVoteRecord()
    {
        return $this->hasMany(RecordedVoteRecord::class);
    }

    /**
     * All votes which have been cast on the motion
     */
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }


}
