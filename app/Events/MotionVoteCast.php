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
    protected $motion;

    protected $meeting;
    public $count;
    /**
     * @var int
     */
    public $totalMembers;
    /**
     * @var mixed
     */
    public $motion_id;
    public $meeting_id;

    /**
     * Create a new event instance.
     *
     * @param Motion $motion
     */
    public function __construct(Motion $motion)
    {
        $this->motion = $motion;
        $this->meeting = $motion->meeting;

        $this->motion_id = $this->motion->id;

        $this->meeting_id = $this->meeting->id;

        $this->count = $motion->totalVotesCast;
        $this->totalMembers = collect($this->meeting->users)->count();
    }

    public function broadcastWith()
    {
        return [
            "motion" => [
                "meeting_id" => $this->meeting->id,
                "id" => $this->motion->id
            ],
            "meeting" => [
                "id" => $this->meeting->id,
            ],
            "count" => $this->count,
            "totalMembers" => $this->totalMembers
        ];
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
