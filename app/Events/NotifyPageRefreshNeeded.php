<?php

namespace App\Events;

use App\Exceptions\PageRefreshNeededException;
use App\Models\Meeting;
use App\Models\Motion;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyPageRefreshNeeded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, ChannelDefinitionTrait, GeneralNotificationTrait;

    const ERROR_CODE = 801;

    const MESSAGE = "New content is available. Please refresh your browser to see it";

    const MESSAGE_STYLE = 'danger';

    const DISPLAY_TIME = 15000;

    /** @var bool Whether the user must dismiss the message */
    const BLOCKING_MESSAGE = true;



    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel($this->meetingChannelName());
    }


}
