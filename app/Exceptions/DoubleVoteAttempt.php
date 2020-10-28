<?php

namespace App\Exceptions;

use Exception;

class DoubleVoteAttempt extends Exception
{

    public $errorCode = 501;
    //
}
