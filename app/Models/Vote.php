<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;



    public function is_abstention(){
        return is_null($this->is_yay);
    }



    public function motion(){
        return $this->belongsTo(Motion::class);

    }

    public function user(){
        return $this->belongsTo(User::class);
    }


}
