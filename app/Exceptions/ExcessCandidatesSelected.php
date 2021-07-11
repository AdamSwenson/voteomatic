<?php

namespace App\Exceptions;

use Exception;

class ExcessCandidatesSelected extends Exception
{

    const ERROR_CODE = 521;

    const MESSAGE = "Too many candidates selected for office";
    //
}
