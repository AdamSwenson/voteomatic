<?php

namespace App\Events;

use App\Models\Motion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Requests that the provided motion be set as current. Sends only the motion id
 */
class RequestClientSetCurrentMotionById implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, ChannelDefinitionTrait, SendWithMotionIdOnlyTrait;


    protected $motion;
    protected $meeting;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Motion $motion)
    {
        $this->motion = $motion;
        $this->meeting = $motion->meeting;
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
