<?php


namespace App\LTI\Exceptions;

use Exception;


/**
 * Class LTIAuthenticationException
 * Parent for all failures to authenticate via LTI
 *
 * @package App\LTI\Exceptions
 */
class LTIAuthenticationException  extends Exception
{

    protected $code = 408;

    protected $message = 'Error in LTI authentication';

}
