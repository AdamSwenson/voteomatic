<?php

namespace App\Exceptions;

use Exception;

class IneligibleSecondAttempt extends ClientVisibleException
{

    const ERROR_CODE = 531;

    const MESSAGE = "Don't be silly. You can't second your own motion";

    const MESSAGE_STYLE = 'danger';

    const DISPLAY_TIME = 5000;

    /** @var bool Whether the user must dismiss the message */
    const BLOCKING_MESSAGE = true;

//
//    public function __construct($motion=null)
//    {
//
//        $this->motion = $motion;
//    }


    //
}
