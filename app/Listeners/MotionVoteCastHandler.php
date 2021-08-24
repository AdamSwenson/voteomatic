<?php

namespace App\Listeners;

use App\Events\MotionVoteCast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MotionVoteCastHandler
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
     * @param  MotionVoteCast  $event
     * @return void
     */
    public function handle(MotionVoteCast $event)
    {
        //
    }
}
