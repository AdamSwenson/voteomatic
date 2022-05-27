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
 * Class MotionNeedingApproval
 *
 * Raised when a new motion has been created and it's text updated.
 * Tells the client to ask the chair to approve it as in order
 *
 * @package App\Events
 */
class MotionNeedingApproval implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, ChannelDefinitionTrait, SendWithMotionOnlyTrait;

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
        return new PrivateChannel($this->chairChannelName());

//        return new PrivateChannel('chair.'.$this->meeting->id);
    }
}
