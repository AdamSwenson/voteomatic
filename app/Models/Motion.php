<?php

namespace App\Models;

use App\Models\Assignment;
use App\Models\Election\Candidate;
use App\Models\Election\PoolMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motion extends Model
{
    use HasFactory;

    protected $fillable = [
        /** The user who ruled on whether the motion is in order */
        'approver_id',
        /** The user who made the motion */
        'author_id',
        'applies_to',
        'content',
        'description',
        'debatable',

        /** JSON field for storing stuff needed by different forms of motion */
        'info',
        'info->propositionName',

        'is_complete',
        'is_current',
        'is_in_order',
        /** Whether it is mutli-line html text where formatting matters */
        'is_resolution',
        /** Whether users are allowed at this time to cast votes on the motion */
        'is_voting_allowed',
        /** For elections, this defines how many people can be elected to the office */
        'max_winners',
        'meeting_id',
        'requires',
        'superseded_by',
        /** The user who seconded the motion */
        'seconder_id',

        'seconded',
        'type'];

    /**
     * All legitimate motion type names
     * @var string[]
     */
    protected $motionTypes = [
        'amendment',
        'amendment-secondary',
        /** An office voted upon during an election */
        'election',
        'main',
        'privileged',
        'procedural-main',
        'procedural-subsidiary',
        /** A proposal voted upon during an election */
        'proposition',
        'incidental'
    ];

    /**
     * Names of motion types which denote that
     * the motion should be handled as an amendment
     * of another motion.
     * This allows everything else to not worry about
     * how we name the various possible forms of amendments
     * @var array
     */
    static public $amendmentTypes = [
        'amendment',
        'amendment-secondary',
    ];

    /**
     * Names of motion types which denote that
     * the motion should be handled as an procedural
     * motion.
     * This allows everything else to not worry about
     * how we name the various possible forms of procedural motion
     * @var array
     */
    static public $proceduralTypes = [
        'privileged',
        'procedural-main',
        'procedural-subsidiary',
        'incidental'
    ];


    protected $casts = [
        'info' => 'array',
        'is_complete' => 'boolean',
        'is_current' => 'boolean',
        'is_in_order' => 'boolean',
        'is_voting_allowed' => 'boolean',
        'is_resolution' => 'boolean',
        'debatable' => 'boolean',
        'seconded' => 'boolean',
        //election
        'max_winners' => 'integer'
    ];

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
    public function addSubsidiaryMotion(Motion $subsidiaryMotion)
    {
        $subsidiaryMotion->applies_to = $this->id;
        $subsidiaryMotion->save();
    }

    /**
     * Returns all subsidiary direct descendent motions, FILO ordered
     */
    public function getSubsidiaryMotions()
    {
        return Motion::where('applies_to', $this->id)->orderBy('id', 'desc')->get(); //->sortDesc();
    }

    /**
     * When a motion has been altered by a subsidary action
     * (e.g., an amendment), a separate process will create a
     * new motion with the amended text which supersedes this motion.
     * That motion gets passed in here, and the present motion
     * is set as superseded by it.
     *
     * When a motion is superseded, it should not appear in the
     * motion tree as it was never voted upon.
     *
     * @param Motion $supersedingMotion
     */
    public function markSuperseded(Motion $supersedingMotion)
    {
        $this->superseded_by = $supersedingMotion->id;
        $this->save();
    }


    /**
     * Returns the motion which replaced this motion
     * after an amendment
     * @return mixed
     */
    public function getSupersedingMotion()
    {
        if (!is_null($this->superseded_by)) {
            return Motion::where('id', $this->superseded_by)->first();
        }
    }

    /**
     * Returns true if the motion is not subsidiary to any
     * other motion
     * NB, includes incidental motions
     */
    public function isMainMotion()
    {
        return is_null($this->applies_to);
    }

    public function isAmendment()
    {
        $types = collect(self::$amendmentTypes);
        return $types->contains($this->type);
    }

    public function isProcedural()
    {
        $types = collect(self::$proceduralTypes);
        return $types->contains($this->type);
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
        if($this->requires == 0.5){
            //A majority is more than half
            return count($this->affirmativeVotes) > $this->voteCountThreshold;
        }
        else{
            //2/3 votes (and hopefully most others require at least 2/3
            return count($this->affirmativeVotes) >= $this->voteCountThreshold;
        }

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
     * The number of votes which the affirmatives must meet or exceed
     * for the motion to pass
     * todo Should this round up? Is there any case where that matters?
     * NB, Majority votes must be greater than this value. 2/3 votes must be at least
     * this value
     */
    public function getVoteCountThresholdAttribute()
    {
        return $this->totalVotesCast * $this->requires;

//        return $this->attributes['totalVotesCast'] * $this->requires;
    }

    public function author()
    {
        return User::find($this->author_id);
    }

    public function setAuthor(User $user)
    {
        $this->author_id = $user->id;
        $this->save();
    }

    /**
     * If the chair creates the motion, it is automatically seconded
     * with them as the seconder.
     * @return mixed
     */
    public function seconder()
    {
        return User::find($this->seconder_id);
    }


    public function setSecond(User $user)
    {
        $this->seconder_id = $user->id;
        $this->save();
    }

//    public function markInOrder(User $user)
//    {
//        $this->approver_id = $user->id;
//        $this->is_in_order = true;
//        $this->save();
//    }

    /**
     * Returns the person who ruled on the orderliness of the motion
     *
     * @return mixed
     */
    public function approver()
    {
        return User::find($this->approver_id);
    }

    /**
     * Determines whether someone other than the author
     * is seconding.
     *
     * IF THE USER IS THE CHAIR, THEY WILL BE ALLOWED TO DO SO
     * SINCE PRESUMABLY THEY ARE RECORDING WHAT HAS HAPPENED VERBALLY
     *
     * @param User $potentialSecond
     * @return bool
     */
    public function isEligibleToSecond(User $potentialSecond)
    {
        //check if they are the chair
        if ($this->meeting->isOwner($potentialSecond)) {
            return true;
        }

        //if not, they must not be identical to the author
        $author = $this->author();
        return !$author->is($potentialSecond);
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

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function poolMembers()
    {
        return $this->hasMany(PoolMember::class);
    }

}
