<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RecordedVoteRecord
 * Represents the fact that someone has voted on a given motion.
 *
 * Has no timestamps to prevent correlation with recorded votes.
 *
 * @package App\Models
 */
class RecordedVoteRecord extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class);

    }

    public function motion(){
        return $this->belongsTo(Motion::class);
    }
}
