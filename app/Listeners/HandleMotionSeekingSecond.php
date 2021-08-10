<?php

namespace App\Listeners;

use App\Events\MotionSeekingSecond;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleMotionSeekingSecond
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
     * @param  MotionSeekingSecond  $event
     * @return void
     */
    public function handle(MotionSeekingSecond $event)
    {
        //
    }
}
