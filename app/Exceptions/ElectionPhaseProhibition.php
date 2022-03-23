<?php

namespace App\Exceptions;


class ElectionPhaseProhibition extends ClientVisibleException
{

    const ERROR_CODE = 560;

    const MESSAGE_STYLE = 'danger';

    const DISPLAY_TIME = null;

    /** @var bool Whether the user must dismiss the message */
    const BLOCKING_MESSAGE = true;

    const MESSAGE = "You are not allowed to access this resource during the current phase of the election";

}
