<?php

namespace App\Exceptions;

use Exception;

class IneligibleMeetingCreator extends ClientVisibleException
{

    const ERROR_CODE = 504;

    const MESSAGE = "You are not allowed to create a meeting";

    const MESSAGE_STYLE = 'danger';

    const DISPLAY_TIME = 5000;

    /** @var bool Whether the user must dismiss the message */
    const BLOCKING_MESSAGE = true;

}
