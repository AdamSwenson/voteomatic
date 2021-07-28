<?php

namespace Tests\Events;

use App\Events\MotionClosed;

//use PHPUnit\Framework\TestCase;
use App\Models\Motion;
use Tests\TestCase;

class MotionClosedTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

    }


    public function testDispatching(){
        $motion = Motion::factory()->create();

        MotionClosed::dispatch($motion);
    }

}
