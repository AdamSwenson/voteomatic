<?php

namespace App\Models;

use App\ResourceLink;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable= ['date','name'];

    /**
     * All the motions introduced at the meeting
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function motions(){
    return $this->hasMany(Motion::class);
    }

    public function resourceLink(){
        return $this->hasOne(ResourceLink::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }
}
