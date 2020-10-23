<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    const ALLOWED_VOTE_TYPES = ['yay', 'nay'];



    public function is_abstention(){
        return is_null($this->is_yay);
    }


public function makeReceiptHash(){
        //todo This isn't the best way to handle
    $time = microtime(true);
    $receipt = bcrypt($time);

    $this->attributes['receipt'] = $receipt;
//    $this->save();

}

    public function motion(){
        return $this->belongsTo(Motion::class);

    }

    public function user(){
        return $this->belongsTo(User::class);
    }


}
