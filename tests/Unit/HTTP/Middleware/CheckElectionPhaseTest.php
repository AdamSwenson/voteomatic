<?php

namespace Tests\Http\Middleware;

use App\Exceptions\ElectionPhaseProhibition;
use App\Http\Middleware\CheckElectionPhase;

//use PHPUnit\Framework\TestCase;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Mockery\Mock;
use Tests\TestCase;

class CheckElectionPhaseTest extends TestCase
{
    public $election;
public $user;
public $motion;
public $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->object = new CheckElectionPhase();
    }

    public function initialize(){
        $this->election->addUserToMeeting($this->user);
        Auth::login($this->user);
        $this->motion = Motion::factory()->create(['meeting_id' => $this->election->id]);
$this->request = Mockery::mock(Request::class);
$p = Mockery::mock();
$p->shouldReceive('parameters')->andReturn($this->motion->id);
$this->request->shouldReceive('route')->andReturn($p);
    }


//    /** @test */
//    public function passesThroughIfNotElection()
//    {
//        $this->election = Meeting::factory()->electionSetupPhase()->create();
//        $this->initialize();
//
////        $this->object->handle()
////        $route = "election/$this->motion->id/results";
////$request = new Request();
////$request->route = $route;
//    }


    // ============================== Admin tests
    /** @test */
    public function adminSetupPhase()
    {
        $this->user = User::factory()->administrator()->create();
        $this->election = Meeting::factory()->electionSetupPhase()->create();
$this->initialize();

$result = $this->object->checkAdmin($this->election);
$this->assertEquals(true, $result);
//$this->asssertTrue($result);
    }

    /** @test */
    public function adminNominationsPhase()
    {
        $this->user = User::factory()->administrator()->create();

        $this->election = Meeting::factory()->electionNominationsPhase()->create();
        $this->initialize();

        $result = $this->object->checkAdmin($this->election);
        $this->assertEquals(true, $result);
    }
    /** @test */
    public function adminVotingPhase()
    {
        $this->user = User::factory()->administrator()->create();

        $this->election = Meeting::factory()->electionVotingPhase()->create();
        $this->initialize();

        $result = $this->object->checkAdmin($this->election);
        $this->assertEquals(true, $result);
    }
    /** @test */
    public function adminClosedPhase()
    {
        $this->user = User::factory()->administrator()->create();

        $this->election = Meeting::factory()->electionClosedPhase()->create();
        $this->initialize();

        $result = $this->object->checkAdmin($this->election);
        $this->assertEquals(true, $result);
    }

    /** @test */
    public function adminResultsPhase()
    {
        $this->user = User::factory()->administrator()->create();

        $this->election = Meeting::factory()->electionResultsPhase()->create();
        $this->initialize();

        $result = $this->object->checkAdmin($this->election);
        $this->assertEquals(true, $result);
    }


    // ============================== User tests

    /** @test */
    public function regUserSetupPhase()
    {
        $this->user = User::factory()->create();
        $this->election = Meeting::factory()->electionSetupPhase()->create();
        $this->initialize();

$this->expectException(ElectionPhaseProhibition::class);
        $result = $this->object->checkRegUser($this->election);
    }

    /** @test */
    public function regUserNominationsPhase()
    {
        $this->user = User::factory()->create();
        $this->election = Meeting::factory()->electionNominationsPhase()->create();
        $this->initialize();
        $this->expectException(ElectionPhaseProhibition::class);
        $result = $this->object->checkRegUser($this->election);
    }
    /** @test */
    public function regUserVotingPhase()
    {
        $this->user = User::factory()->create();
        $this->election = Meeting::factory()->electionVotingPhase()->create();
        $this->initialize();
        $result = $this->object->checkRegUser($this->election);
        $this->assertEquals(true, $result);
    }
    /** @test */
    public function regUserClosedPhase()
    {
        $this->user = User::factory()->create();
        $this->election = Meeting::factory()->electionClosedPhase()->create();
        $this->initialize();

        $this->expectException(ElectionPhaseProhibition::class);
        $result = $this->object->checkRegUser($this->election);

    }

    /** @test */
    public function regUserResultsPhase()
    {
        $this->user = User::factory()->create();
        $this->election = Meeting::factory()->electionResultsPhase()->create();
        $this->initialize();
        $result = $this->object->checkRegUser($this->election);
        $this->assertEquals(true, $result);
    }



}
