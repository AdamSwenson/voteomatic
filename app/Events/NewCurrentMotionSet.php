<?php

namespace App\Events;

use App\Exceptions\PageRefreshNeededException;
use App\Models\Motion;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewCurrentMotionSet implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, ChannelDefinitionTrait;
    /**
     * @var Motion
     */
    public $motion;

    public $meeting;

    /**
     * Create a new event instance.
     *
     * @param Motion $motion
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
        try {
            return new PrivateChannel($this->meetingChannelName());

        }catch (BroadcastException $e) {
//            dd($e);
//        throw new PageRefreshNeededException();
        }
    }

}
