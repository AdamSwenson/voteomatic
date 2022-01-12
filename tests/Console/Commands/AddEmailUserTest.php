<?php

namespace Tests\Console\Commands;

use App\Console\Commands\AddEmailUser;
use App\Models\Meeting;
use App\Models\User;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class AddEmailUserTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

    }

    /** @test */
    public function handleWithPasswordSpecified()
    {
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $email = $this->faker->email;
        $password = $this->faker->word;

        $cmd = 'users:add ' . $firstName . ' ' . $lastName . ' ' . $email . ' ' . $password;

        $this->artisan($cmd)->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName
        ]);
    }

    /** @test */
    public function handleWithNoPasswordSpecified()
    {
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $email = $this->faker->email;
//        $password = $this->faker->word;

        $cmd = 'users:add ' . $firstName . ' ' . $lastName . ' ' . $email;

        $this->artisan($cmd)->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName
        ]);

        //check that a password was created
        $u = User::where('email', $email)->first();
        $this->assertNotEmpty($u->password);
    }

    /** @test */
    public function handleWhenCreatingAdmin()
    {
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $email = $this->faker->email;
        $password = $this->faker->word;

        $cmd = 'users:add ' . $firstName . ' ' . $lastName . ' ' . $email . ' ' . $password . ' --admin';

        $this->artisan($cmd)->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName
        ]);


        $u = User::where('email', $email)->first();
        $this->assertTrue($u->is_admin);
    }

    /** @test */
    public function handleWhenCreatingDemo()
    {
        $meetingCount = sizeof(Meeting::all());
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $email = $this->faker->email;
        $password = $this->faker->word;

        $cmd = 'users:add ' . $firstName . ' ' . $lastName . ' ' . $email . ' ' . $password . ' --demo';

        $this->artisan($cmd)->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName
        ]);

        $this->assertTrue(sizeof(Meeting::all()) > $meetingCount, "New meeting(s) created");
        $u = User::where('email', $email)->first();

        $m = Meeting::where('owner_id', $u->id)->first();
        $this->assertNotEmpty($m, "User set as meeting owner");
        $this->assertTrue($m->isPartOfMeeting($u), "user added to meeting members");

    }

    /** @test */
    public function checkApostropheContainingUserName()
    {
        //The apostrophe in the last name causes it to record the last name as 'O'
        //and the email as "Hara"

        $email = "zcrist@dubuque.net";
        $firstName = "Albin";
        $lastName = "O'Hara";

        $cmd = 'users:add ' . $firstName . ' ' . $lastName . ' ' . $email;

        $this->artisan($cmd)->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName
        ]);

    }

//    /** @test */
//    public function testgetPassword()
//    {
////$obj = new AddEmailUser();
//////dd($obj);
////$r = $obj->getPassword();
////$this->assertEquals($r, 't');
//////dd($r);
//    }

}
