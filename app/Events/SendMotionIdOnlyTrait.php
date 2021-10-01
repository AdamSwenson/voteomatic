<?php

namespace App\Events;

/**
 * Used to lower size of pusher payloads
 * when the client won't need more than just the motion id
 */
trait SendMotionIdOnlyTrait
{

}
