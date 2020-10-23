<?php

namespace App;

use App\Models\Meeting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceLink extends Model
{
    use HasFactory;

    /**
     * The consuming application (canvas) info
     * todo For now this is just one to one. consider expanding if others use
     */
    public function ltiConsumer(){
        return $this->belongsTo(LTIConsumer::class);
    }

    public function meeting(){
        return $this->belongsTo(Meeting::class);
    }
}
