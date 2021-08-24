<?php

namespace App\Listeners;

use App\Events\MotionNeedingApproval;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleNewMotionCreated
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
     * @param  MotionNeedingApproval  $event
     * @return void
     */
    public function handle(MotionNeedingApproval $event)
    {
        //
    }
}
