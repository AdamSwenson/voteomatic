<?php

namespace App\Exceptions;

use App\Models\Motion;

class ElectionPermalockException extends ClientVisibleException
{

    const ERROR_CODE = 590;

    const MESSAGE_STYLE = 'danger';

    const DISPLAY_TIME = 10000;

    /** @var bool Whether the user must dismiss the message */
    const BLOCKING_MESSAGE = true;

    const MESSAGE = "This election is permanently locked. Voting cannot be enabled.";


}
