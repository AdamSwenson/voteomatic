<?php

namespace App\Exceptions;

use Exception;

class IneligibleSecondAttempt extends Exception
{

    const ERROR_CODE = 531;

    const MESSAGE = "User is ineligible to second the motion";
    //
}
