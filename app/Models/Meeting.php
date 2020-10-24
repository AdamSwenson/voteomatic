<?php

namespace App\Models;

use App\ResourceLink;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    public function resourceLink(){
        return $this->hasOne(ResourceLink::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }
}
