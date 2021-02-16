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
        'name',
        'info',
        'is_write_in',
        'motion_id'
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


    public function votes(){
return $this->hasMany(Vote::class);
    }



}
