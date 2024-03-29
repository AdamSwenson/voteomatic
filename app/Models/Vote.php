<?php

namespace App\Models;

use App\Models\Election\Candidate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    const ALLOWED_VOTE_TYPES = ['yay', 'nay'];

    protected $fillable = [
        'motion_id',
        'candidate_id',
        'receipt'
    ];

    /**
     * This is separate from addReceiptHash to allow us
     * to generate a hash that could be shared by votes, e.g.,
     * in an election ballot.
     *
     * @return string
     */
    static public function makeReceiptHash(){
        //todo This isn't the best way to handle
        $time = microtime(true);
        return  bcrypt($time);
    }

    public function is_abstention()
    {
        return is_null($this->is_yay);
    }


    /**
     * Creates and stores a receipt hash on
     * the model
     */
    public function addReceiptHash()
    {
$receipt = self::makeReceiptHash();
        $this->attributes['receipt'] = $receipt;
//    $this->save();

    }

    public function motion()
    {
        return $this->belongsTo(Motion::class);

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * For use in elections only
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }


}
