<?php

namespace Tests\Events;

use App\Events\MotionClosed;
use App\Events\MotionVoteCast;

//use PHPUnit\Framework\TestCase;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use Tests\TestCase;

class MotionVoteCastTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
$this->meeting = Meeting::factory()->create();
    }

    /** @test */
    public function totalMembersCorrect(){
        //This added to fix of VOT-332
        
        $numUsers = 5;
        $numDuplicates = 3;

        $users = User::factory()->count($numUsers)->create();
        $this->assertEquals($numUsers, sizeof($users));

        foreach ($users as $user){
            for($i = 0; $i < $numDuplicates; $i++){
                $this->meeting->addUserToMeeting($user);
            }
        }
       $this->assertEquals($numUsers *$numDuplicates, collect($this->meeting->users)->count(), "All users added to meeting");

        $this->motion = Motion::factory()->create(['meeting_id' => $this->meeting->id]);

        //call
        $motionVoteCast = new MotionVoteCast($this->motion);

        //check
        $this->assertEquals($numUsers, $motionVoteCast->totalMembers, "Duplicate members not reflected in total members");

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
