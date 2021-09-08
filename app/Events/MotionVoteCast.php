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
 * Class MotionVoteCast
 *
 * Gets dispatched when a user votes for a motion.
 * This will be used to inform the chair when voting is done
 *
 * @package App\Events
 */
class MotionVoteCast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, ChannelDefinitionTrait;


    /**
     * @var Motion
     */
    public $motion;

    public $meeting;
    public $count;
    /**
     * @var int
     */
    public $totalMembers;

    /**
     * Create a new event instance.
     *
     * @param Motion $motion
     */
    public function __construct(Motion $motion)
    {
        $this->motion = $motion;
        $this->meeting = $motion->meeting;
        $this->count = $motion->totalVotesCast;
        $this->totalMembers = collect($this->meeting->users)->count();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel($this->chairChannelName());
    }

}
