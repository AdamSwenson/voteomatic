<?php

namespace App\Events;

/**
 * When attached to an event, the pusher payload will contain
 * only a json with the key 'motion' and the motion's attributes
 * as value. This reduces the size since we don't serialize relationships
 *
 * {
 * motion :
 *      {
 *          id : 1,
 *          ...
 *      }
 * }
 */
trait SendWithMotionOnlyTrait
{


    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        //This prevents the relationships from being serialized
        return ['motion' => $this->motion->attributesToArray()];
    }
}
