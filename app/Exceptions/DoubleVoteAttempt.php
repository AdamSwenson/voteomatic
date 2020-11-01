<?php

namespace App\Exceptions;

use Exception;

class DoubleVoteAttempt extends Exception
{

    const ERROR_CODE = 501;

    const MESSAGE = "You have already voted on this motion";
    //
}
