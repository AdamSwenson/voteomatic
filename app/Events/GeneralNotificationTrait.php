<?php

namespace App\Events;

use App\Models\Meeting;

/**
 * Messages which go out to all members of the meeting
 * and are not tied to a particular motion
 *
 * All child classes must define the following constants:
 *      const ERROR_CODE = '';
 *      const MESSAGE_STYLE = '';
 *      const DISPLAY_TIME = 5000;
 *      const BLOCKING_MESSAGE = false;
 *      const MESSAGE = "";
 */
trait GeneralNotificationTrait
{
//
//    //dev All child classes must define
//    const ERROR_CODE = '';
//
//    const MESSAGE_STYLE = '';
//
//    const DISPLAY_TIME = 5000;
//
//    const BLOCKING_MESSAGE = false;
//
//    const MESSAGE = "";

    /**
     * @var Meeting
     */
    public $meeting;


    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'messageText' => static::MESSAGE,
            'messageStyle' => static::MESSAGE_STYLE,
            'displayTime' => static::DISPLAY_TIME,
            'blockingMessage' => static::BLOCKING_MESSAGE,
            'id' => static::ERROR_CODE,
            'status' => static::ERROR_CODE,
        ];
    }

    public function broadcastAs()
    {
        return 'App\Events\GeneralNotification';
    }

}
