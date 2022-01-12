<?php

namespace App\Listeners;

use App\Events\MotionSeconded;
use App\Exceptions\PageRefreshNeededException;
use App\Repositories\MotionRepository;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

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
        $motion = $event->motion;
        if(! MotionRepository::isPusherCompatible($motion)){
            throw new PageRefreshNeededException();
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  \App\Events\OrderShipped  $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(MotionSeconded $event)
    {

        Log::alert('taaaaaaaaaaaaa');
        // * @param BroadcastException $e
        //         */ function (BroadcastException $e) {
        //             abort(443);
        //dd($e);
        //             throw new PageRefreshNeededException();
    }
}
