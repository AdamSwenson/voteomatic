<?php

namespace App\Listeners;

use App\Events\NewCurrentMotionSet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NewCurrentMotionSet
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
     * @param  NewCurrentMotionSet  $event
     * @return void
     */
    public function handle(NewCurrentMotionSet $event)
    {
        //
    }
}
