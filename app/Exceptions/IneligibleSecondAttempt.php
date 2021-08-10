<?php

namespace App\Exceptions;

use Exception;

class IneligibleSecondAttempt extends Exception
{

    const ERROR_CODE = 531;

    const MESSAGE = "Don't be silly. You can't second your own motion";
    //
}
