<?php

namespace App\Exceptions;

use Exception;

/**
 * Mainly used when a vote has been aborted
 */
class VoteSubmittedWhenNotAllowed extends ClientVisibleException
{
    //

    const ERROR_CODE = 535;

    const MESSAGE = "Voting is not allowed at this time";

    const MESSAGE_STYLE = 'danger';

    const DISPLAY_TIME = 5000;

    /** @var bool Whether the user must dismiss the message */
    const BLOCKING_MESSAGE = true;

}
