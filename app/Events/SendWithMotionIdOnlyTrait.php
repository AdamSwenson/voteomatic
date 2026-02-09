<?php

namespace App\Events;

/**
 * Used to lower size of pusher payloads
 * when the client won't need more than just the motion id
 *
 * Motion (and Meeting, if applicable) must be stored in protected
 * properties otherwise they will be sent to pusher.
 *
 * Client will receive
 *      PusherEvent {
 *          motion_id
 *          motionId
 *      }
 *
 */
trait SendWithMotionIdOnlyTrait
{

    public function broadcastWith()
    {
        return [
            "motion_id" => $this->motion->id,
            "motionId" =>$this->motion->id,
        ];

    }
}
