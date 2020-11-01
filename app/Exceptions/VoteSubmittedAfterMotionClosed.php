<?php

namespace App\Exceptions;

use Exception;

class VoteSubmittedAfterMotionClosed extends Exception
{
    //

    const ERROR_CODE = 505;

    const MESSAGE = "Vote submitted after voting ended";

}
