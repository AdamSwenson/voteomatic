<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MotionStackItem
 *
 * Associates a motion with a particular position in
 * the stack for a given meeting
 *
 * @package App\Models
 */
class MotionStackItem extends Model
{
    use HasFactory;

    public function meeting(){
        return $this->belongsTo(Meeting::class);
    }

    public function motion(){
        return $this->belongsTo(Motion::class);
    }
}
