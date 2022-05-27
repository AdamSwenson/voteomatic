<?php

namespace App\Exceptions;

use App\Models\Motion;

class BadWriteInAttempt extends ClientVisibleException
{

    const ERROR_CODE = 540;

    const MESSAGE_STYLE = 'danger';

    const DISPLAY_TIME = 10000;

    /** @var bool Whether the user must dismiss the message */
    const BLOCKING_MESSAGE = true;

    const MESSAGE = "The name you attempted to write in for this office was not valid";


}
