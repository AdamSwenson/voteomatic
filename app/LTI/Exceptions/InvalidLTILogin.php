<?php


namespace App\LTI\Exceptions;

use Exception;

/**
 * Class InvalidLTILogin
 * Generic exception for login errors
 *
 * @package App\LTI\Exceptions
 */
class InvalidLTILogin extends Exception
{
    public function __construct($reason = null, $message = null)
    {
        $this->$reason = $reason;
        parent::__construct($message);
    }


}