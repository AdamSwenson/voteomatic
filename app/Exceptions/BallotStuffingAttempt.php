<?php

namespace App\Exceptions;


class BallotStuffingAttempt extends ClientVisibleException
{

    const ERROR_CODE = 551;

    const MESSAGE_STYLE = 'danger';

    const DISPLAY_TIME = null;

    /** @var bool Whether the user must dismiss the message */
    const BLOCKING_MESSAGE = true;

    const MESSAGE = "The same candidate was voted for more than once, please try again. You may need to
    refresh your browser";

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
