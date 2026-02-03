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
 * If the motion object would be too big for pusher, this
 * requests that the client reload the motion from the server
 */
class RequestClientReloadMotion implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, ChannelDefinitionTrait;

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
