<?php

namespace App\Exceptions;


class WriteInDuplicatesOfficial extends ClientVisibleException
{

    const ERROR_CODE = 550;

    const MESSAGE_STYLE = 'danger';

    const DISPLAY_TIME = null;

    /** @var bool Whether the user must dismiss the message */
    const BLOCKING_MESSAGE = true;

    const MESSAGE = "The name you attempted to write in matches someone who is already a candidate for this office.
    If you are attempting to write in a different person with the same name as a candidate, please do
    not cast your vote and contact the election administrator.";

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
