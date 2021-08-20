<?php

namespace App\Listeners;

use App\Events\VotingOnMotionOpened;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class VotingOnMotionOpenedHandler
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
     * @param  VotingOnMotionOpened  $event
     * @return void
     */
    public function handle(VotingOnMotionOpened $event)
    {
        //
    }
}
