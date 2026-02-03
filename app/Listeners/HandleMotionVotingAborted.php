<?php

namespace App\Listeners;

use App\Events\VotingOnMotionAborted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleMotionVotingAborted
{
    public $motion;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(VotingOnMotionAborted $event)
    {
        $this->motion = $event->motion;
    }

    /**
     * Handle the event.
     *
     *
     * @return void
     */
    public function handle(VotingOnMotionAborted $event)
    {
        //
    }
}
