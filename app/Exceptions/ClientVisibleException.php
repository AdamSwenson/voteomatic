<?php

namespace App\Exceptions;

use App\Models\Motion;
use Exception;

/**
 * This is the parent for anything which wants to
 * send a message which the user will have displayed for them
 * on the client
 */
class ClientVisibleException extends Exception
{
//dev all children must define the following constants
//    const ERROR_CODE = ;
//
//    const MESSAGE_STYLE = '';
//
//    const DISPLAY_TIME = 5000;
//
//    const BLOCKING_MESSAGE = false;
//
//    const MESSAGE = "";

    /**
     * Text for the user to read
     * @var string
     */
    public $messageText;

    /**
     * Milliseconds before auto hides
     * @var int
     */
    public $displayTime;

    /**
     * Whether the user must dismiss the message
     * @var bool
     */
    public $blockingMessage;

    /**
     * Will be used by client to manage the message
     * @var int
     */
    public $id;

    /**
     * If a motion is included, the text will be displayed
     * @var null
     */
    public $motion;

    /**
     * Numerical error status code. Will be used as id
     * @var int
     */
    public $status;

    /**
     * Bootstrap styling string:
     *      danger
     *      primary
     *      warning
     *      info
     *      etc
     * @var string
     */
    public $messageStyle;

    public function __construct($motion=null)
    {
        $this->messageText = static::MESSAGE;
        $this->messageStyle = static::MESSAGE_STYLE;
        $this->displayTime = static::DISPLAY_TIME;
        $this->blockingMessage = static::BLOCKING_MESSAGE;
        $this->id = static::ERROR_CODE;
        $this->status = static::ERROR_CODE;
        $this->motion = $motion;
    }
    //
}
