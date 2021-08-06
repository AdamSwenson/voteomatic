<?php

namespace App\Listeners;

use App\Events\MotionSeconded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleMotionSeconded
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
     * @param  MotionSeconded  $event
     * @return void
     */
    public function handle(MotionSeconded $event)
    {
        //
    }
}
