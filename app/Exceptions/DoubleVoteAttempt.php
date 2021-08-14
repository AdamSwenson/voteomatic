<?php

namespace App\Exceptions;

use App\Models\Motion;

class DoubleVoteAttempt extends ClientVisibleException
{

    const ERROR_CODE = 501;

    const MESSAGE_STYLE = 'danger';

    const DISPLAY_TIME = 5000;

    /** @var bool Whether the user must dismiss the message */
    const BLOCKING_MESSAGE = true;

    const MESSAGE = "You have already voted on this motion";

//
//    /**
//     * @var string
//     */
//    public $messageText;
//    /**
//     * @var int
//     */
//    public $displayTime;
//    /**
//     * @var bool
//     */
//    public $blockingMessage;
//    /**
//     * @var int
//     */
//    public $id;
//    /**
//     * @var null
//     */
//    public $motion;
//
//    public function __construct($motion=null)
//    {
//        $this->messageText = self::MESSAGE;
//        $this->displayTime = self::DISPLAY_TIME;
//        $this->blockingMessage = self::BLOCKING_MESSAGE;
//        $this->id = self::ERROR_CODE;
//        $this->motion = $motion;
//    }
//    //
}
