<?php

namespace App\Exceptions;

use Exception;

class VoteSubmittedAfterMotionClosed extends ClientVisibleException
{
    //

    const ERROR_CODE = 505;

    const MESSAGE = "Your vote was submitted after voting ended";

    const MESSAGE_STYLE = 'danger';

    const DISPLAY_TIME = 5000;

    /** @var bool Whether the user must dismiss the message */
    const BLOCKING_MESSAGE = true;

}
