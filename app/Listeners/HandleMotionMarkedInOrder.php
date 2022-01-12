<?php

namespace App\Listeners;

use App\Events\MotionMarkedInOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleMotionMarkedInOrder
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
     * @param  MotionMarkedInOrder  $event
     * @return void
     */
    public function handle(MotionMarkedInOrder $event)
    {
        //
    }
}
