<?php

namespace App\Exceptions;

use Exception;

class PageRefreshNeededException extends ClientVisibleException
{
    //

    const ERROR_CODE = 520;

    const MESSAGE = "New content is available. Please refresh your browser to see it";

    const MESSAGE_STYLE = 'danger';

    const DISPLAY_TIME = 5000;

    /** @var bool Whether the user must dismiss the message */
    const BLOCKING_MESSAGE = true;

}
