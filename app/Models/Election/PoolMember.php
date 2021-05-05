<?php

namespace App\Models\Election;

use App\Models\Motion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PoolMember
 *
 * A person who is eligible to be nominated as a candidate for
 * a given elected office/position
 *
 * @package App\Models\Election
 */
class PoolMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'info',
        //the election they are eligible to be nominated in
        'motion_id',
    ];


    public function getNameAttribute(){
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * The motion representing the office
     * they could be a candidate for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function motion(){
        return $this->belongsTo(Motion::class);
    }

}
