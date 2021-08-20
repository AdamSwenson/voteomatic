<?php

namespace App\Listeners;

use App\Events\MotionMarkedOutOfOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleMotionMarkedOutOfOrder
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MotionMarkedOutOfOrder  $event
     * @return void
     */
    public function handle(MotionMarkedOutOfOrder $event)
    {
        //
    }
}
