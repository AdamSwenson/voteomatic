<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UsedNonce
 * Represents a unique(-ish) oath nonce value which has already been
 * used in the login process
 *
 * @package App
 */
class UsedNonce extends Model
{
    use HasFactory;

    //
}
