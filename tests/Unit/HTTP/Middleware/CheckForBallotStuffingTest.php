<?php

namespace Tests\Http\Middleware;

use App\Exceptions\BallotStuffingAttempt;
use App\Http\Middleware\CheckForBallotStuffing;

//use PHPUnit\Framework\TestCase;

use App\Models\Motion;
use Closure;
use Illuminate\Http\Request;
use Tests\TestCase;

class CheckForBallotStuffingTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->object = new CheckForBallotStuffing();
    }


    /** @test */
    public function containsDuplicatesWhenNoDuplicates()
    {
        $request = new Request();
        $request->candidateIds = [2, 3, 4, 5, 6];
        $result = $this->object->containsDuplicates($request);

        $this->assertFalse($result);
    }

    /** @test */
    public function containsDuplicatesWhenDuplicates()
    {
        $request = new Request();
        $request->candidateIds = [2, 3, 3, 4, 5, 6];
        $result = $this->object->containsDuplicates($request);

        $this->assertTrue($result);
    }

//    /** @test */
//    public function handleNoDuplicates()
//    {
//        $ids = ['candidateIds' => [2, 3,  4, 5, 6]];
//        $motion = Motion::factory()->create();
//        $route = 'election/vote/' . $motion->id;
//
//        $response = $this->post($route,$ids);
//
//        $response->assertStatus(200);
////        $c = function($v){};
////$request = new Request();
////$request->candidateIds = [2, 3, 3, 4, 5, 6];
////$request->route = $route;
////$this->object->handle($request, $c);
//    }


//
//    /** @test */
//    public function handleWhenDuplicates()
//    {
//$this->expectException(BallotStuffingAttempt::class);
//
//        $ids = ['candidateIds' => [2, 3,4,  4, 5, 6]];
//        $motion = Motion::factory()->create();
//        $route = 'election/vote/' . $motion->id;
//
//        $response = $this->post($route,$ids);
//
//
//    }
}
