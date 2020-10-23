<?php

//namespace RobertBoes\LaravelLti\Exceptions;

namespace App\LTI\Exceptions;
use Exception;
use Throwable;

class ToolProviderNotSetException extends Exception
{
    public function __construct($message = "Provider was not set", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}