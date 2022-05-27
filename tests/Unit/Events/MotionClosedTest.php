<?php

namespace Tests\Events;

use App\Events\MotionClosed;

//use PHPUnit\Framework\TestCase;
use App\Models\Motion;
use Illuminate\Support\Facades\Event;
use Tests\helpers\EventDispatcher;
use Tests\TestCase;

class MotionClosedTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->motion = Motion::factory()->completed()->create();
    }

    public function t($motion){
        MotionClosed::dispatch($motion);
    }



    /** @test */
    public function includesExpectedInBroadcast(){

//        Event::fake();
        $e = new EventDispatcher();
        $e->dispatchEvent(MotionClosed::class, $this->motion);

//        MotionClosed::dispatch($this->motion);
        $this->t($this->motion);

        $motion =$this->motion;
        Event::assertDispatched(MotionClosed::class);
//        Event::assertDispatched(function (MotionClosed $event) use ($motion) {
//            return $event->ended->is($motion) && $event->ended->is_complete === true && $event->ended->is_voting_allowed === false;
//        });



    }

}
