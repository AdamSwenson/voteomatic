<?php

namespace App\Events;

use App\Models\Meeting;
use App\Models\Motion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ForcePageReload implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, ChannelDefinitionTrait;

    public mixed $meeting;

    /**
     * Create a new event instance.
     *
     * @return void
     */
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
