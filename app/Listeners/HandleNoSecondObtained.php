<?php

namespace App\Listeners;

use App\Events\NoSecondObtained;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleNoSecondObtained
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
     * @param  NoSecondObtained  $event
     * @return void
     */
    public function handle(NoSecondObtained $event)
    {
        //
    }
}
