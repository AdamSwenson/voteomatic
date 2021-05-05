<?php

namespace App\Models\Election;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Person
 *
 * Someone who can be a pool member or a candidate
 * for arbitrary offices in arbitrary elections
 *
 * @package App\Models\Election
 */
class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'info',
    ];

    protected $casts = [
        'info' => 'array'
    ];

    public function candidates(){
return $this->hasMany(Candidate::class);
    }

    public function poolMembers(){
        return $this->hasMany(PoolMember::class);
    }


}
