<?php

namespace App\Models\Election;

use App\Models\Motion;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Candidate
 *
 * Represents one person running for one office in one election
 *
 * Conveniently this means each candidate is uniquely associated with
 * a motion. That makes life easier w/r/t votes etc
 *
 * @package App\Models\Election
 */
class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'info',
        'is_write_in',
        'motion_id',
        'pool_member_id'
    ];

    /**
     * Returns the total votes cast for the candidate
     * @return int
     */
    public function getVoteTotal(){
        return count($this->votes()->get());
    }


    /**
     * Property version
     * @return int
     */
    public function getTotalVotesReceivedAttribute(){
        return $this->getVoteTotal();
    }

    /**
     * Returns the percentage of the votes cast in the election
     * as a float between 0.0 - 1.0
     * (Looks up the motion details itself since the motion id is stored on
     * the candidate)
     * @return float|int
     */
    public function getShareOfVotesCast(){
        return $this->motion->totalVotesCast > 0 ? $this->totalVotesReceived / $this->motion->totalVotesCast : 0;
    }


    /**
     * Returns the non-write in candidates
     * @param $query
     * @return mixed
     */
    public function scopeOfficial($query)
    {
        return $query->where('is_write_in', '=', false)->orWhere('is_write_in', '=', null);
    }


    public function scopeWriteIn($query){
        return $query->where('is_write_in', true);
    }


    /*
     *  Relationships
     */

    /**
     * The motion representing the office
     * they are a candidate for.
     *
     * Think of it as the motion: That the following person be elected to the office of x
     *
     * The votes are then for who fills in the blank.
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function motion(){
        return $this->belongsTo(Motion::class);
    }

    /**
     * The candidate when they were
     * a pool member (if they were)
     */
    public function poolMember(){
        return $this->hasOne(PoolMember::class);
    }


    public function votes(){
return $this->hasMany(Vote::class);
    }



}
