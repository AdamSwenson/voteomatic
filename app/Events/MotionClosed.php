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
use Illuminate\Support\Facades\Auth;

/**
 * Class MotionClosed
 * Thrown when the chair marks the vote on
 * a motion closed.
 *
 * @package App\Events
 */
class MotionClosed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Motion
     */
    public $motion;
    /**
     * @var Motion
     */
    public $ended;
    /**
     * @var Motion
     */
    public $superseding;
    public $original;

    /**
     * Create a new event instance.
     *
     * @param Motion $ended
     * @param $superseding Motion|false
     */
    public function __construct(Motion $ended,  $superseding=false)
    {
        $this->ended = $ended;
        $this->superseding = $superseding;
//    $this->original = Motion::find($this->ended->applies_to);
    }

    public function broadcastWith()
    {
        //This prevents the relationships from being serialized
        return [
            'ended' => $this->ended->attributesToArray(),
            'superseding' => $this->superseding
        ];
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('motions.'.$this->ended->id);
    }

//    public function broadcastWith(){
//        return [
//            'id' => $this->motion->id
//        ];
//
//    }
}
