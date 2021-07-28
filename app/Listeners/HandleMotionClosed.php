<?php

namespace App\Listeners;

use App\Events\MotionClosed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleMotionClosed
{
    /**
     * @var \App\Models\Motion
     */
    public $motion;

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
     * @param  MotionClosed  $event
     * @return void
     */
    public function handle(MotionClosed $event)
    {
        $this->motion = $event->motion;
    }
}
