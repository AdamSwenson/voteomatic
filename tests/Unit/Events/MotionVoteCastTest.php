<?php

namespace Tests\Events;

use App\Events\MotionClosed;
use App\Events\MotionVoteCast;

//use PHPUnit\Framework\TestCase;
use App\Models\Motion;
use Tests\TestCase;

class MotionVoteCastTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        parent::setUp();

        $this->motion = Motion::factory()->completed()->create();
    }



    /** @test */
    public function broadcastWith()
    {
        $this->markTestSkipped('todo');
//        MotionVoteCast::dispatch($this->motion);

    }

    /** @test */
    public function testbroadcastOn()
    {
        $this->markTestSkipped('todo');
    }

}
