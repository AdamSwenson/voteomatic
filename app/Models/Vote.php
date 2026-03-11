<?php

namespace App\Models;

use App\Models\Election\Candidate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Valorin\Random\Random;

class Vote extends Model
{
    use HasFactory;

    const ALLOWED_VOTE_TYPES = ['yay', 'nay'];

    const RECEIPT_LENGTH = 60;
    const RECEIPT_CHUNK = 5;

    protected $fillable = [
        'motion_id',
        'candidate_id',
        'receipt'
    ];

    /**
     * Returns a random string for use in receipts.
     *
     * String will have the form:
     *  y4ELs-BpXWL-KDOL0-osVMA-Fia4U-dxVhF-WkXIf-IiFxX-p8Ugu-tul7i-Tg08F-k079T
     *
     * This is separate from addReceiptHash to allow us
     * to generate a hash that could be shared by votes, e.g.,
     * in an election ballot.
     *
     * In VOT-336, changed from a hash to a dash-delimited string. Did not rename the method
     * to piss off future-Adam.
     *
     * @return string
     */
    static public function makeReceiptHash()
    {
        //Fixed in VOT-336. Using bcrypt on random bytes ran into trouble with null characters
        return Random::dashed($length = self::RECEIPT_LENGTH, $delimiter = '-', $chunkLength = self::RECEIPT_CHUNK, $mixedCase = true);

    }

    public function is_abstention()
    {
        return is_null($this->is_yay);
    }


    /**
     * Creates and stores a receipt on
     * the model
     */
    public function addReceiptHash()
    {
        $receipt = self::makeReceiptHash();
        $this->attributes['receipt'] = $receipt;

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
