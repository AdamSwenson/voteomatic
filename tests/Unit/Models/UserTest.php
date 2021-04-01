<?php

namespace App\Models;

use App\Models\User;
use Tests\TestCase;

//use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function setUp():void
    {
        parent::setUp();
    }

    public function testGetNameAttribute()
    {
        $first = 'Dr. Jellybean';
        $last = 'Waffles, M.D.';
        $expected = "$first $last";

        $user = User::factory(['first_name' => $first, 'last_name' =>
            $last])->create();

        $this->assertEquals($first, $user->first_name, "model setup correctly");

//check
        $this->assertEquals($expected, $user->name, "Name returns correct string");
    }

    public function testMeetings()
    {
$this->markTestSkipped('presently unneeded');
    }

    public function testAdministrates()
    {
        $this->markTestSkipped('presently unneeded');
    }

    public function testVotes()
    {
        $this->markTestSkipped('presently unneeded');
    }

    public function testRecordedVoteRecord()
    {
        $this->markTestSkipped('presently unneeded');
    }
}
