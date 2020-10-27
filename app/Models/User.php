<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'user_id_hash',
        'email',
        'password',
        'sis_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getNameAttribute(){
        return $this->first_name . ' ' . $this->last_name;
//        return $this->attributes['firstName'] . ' ' . $this->attributes['lastName'];
    }


    // ------------------ relationships
    /**
     * Any motions which the user is an administrator for
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function administrates(){
        return $this->belongsToMany(Motion::class, 'motion_admins')->withTimestamps();

    }


    public function meetings(){
        return $this->belongsToMany(Meeting::class);
    }

    public function recordedVoteRecord(){
        return $this->hasMany(RecordedVoteRecord::class);
    }

    public function votes(){
        //todo remove this
        return $this->hasMany(Vote::class);
    }
}
