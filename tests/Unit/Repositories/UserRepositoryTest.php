<?php

namespace App\Repositories;

use App\Http\Requests\LTIRequest;
use App\Models\Meeting;
use App\Models\User;
use App\Repositories\UserRepository;

//use PHPUnit\Framework\TestCase;
use Mockery\Mock;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->object = new UserRepository();

    }

    /** @test */
    public function getUserFromRequest()
    {

        $e = 'abcd';


        $request = new LTIRequest();
        $request->lis_person_name_family = $this->faker->lastName;
        $request->lis_person_name_given = $this->faker->firstName;
        $request->user_id = $this->faker->sha1;
        $meeting = Meeting::factory()->create();

        //call
        $result = $this->object->getUserFromRequest($request, $meeting);

        //check
        $this->assertInstanceOf(User::class, $result);

        $this->assertTrue($result->is($meeting->users()->first()), "user associated with meeting");

    }

    /** @test */
    public function getEmail()
    {
//        $request = new LTIRequest();
//        $request->lis_person_contact_email_primary = $this->faker->email;
//        $request->lis_person_name_family = $this->faker->lastName;
//        $request->lis_person_name_given = $this->faker->firstName;
$request = \Mockery::spy(LTIRequest::class);
$request->shouldReceive('has')->withArgs(['lis_person_contact_email_primary'])->andReturn(true);
        $request->lis_person_contact_email_primary = $this->faker->email;

        $result = $this->object->getEmail($request);

        $this->assertEquals($request->lis_person_contact_email_primary, $result);
    }

    /** @test */
    public function getEmailNotInRequest()
    {
//        $request = new LTIRequest();
//        $request->lis_person_contact_email_primary = $this->faker->email;
        $request = \Mockery::spy(LTIRequest::class);
        $request->shouldReceive('has')->andReturn(false);

        $request->lis_person_name_family = $this->faker->lastName;
        $request->lis_person_name_given = $this->faker->firstName;

        $result = $this->object->getEmail($request);

        $lastName = $request->lis_person_name_family;
        $firstName = $request->lis_person_name_given;

        $expect = "currently-unusable-" . $firstName . '.' . $lastName . '@csun.edu';
        $this->assertEquals($expect, $result);
    }


    /** @test */
    public function updateEmail()
    {
        $request = \Mockery::spy(LTIRequest::class);
        $request->shouldReceive('has')->andReturn(true);
        $request->lis_person_contact_email_primary = $this->faker->email;

        $user = User::factory()->make(['email' => "currently-unusable-firstName.LastName@csun.edu"]);

        $result = $this->object->updateEmail($user, $request);

        $this->assertInstanceOf(User::class, $result, 'Returns user object');
        $this->assertEquals($request->lis_person_contact_email_primary, $result->email);

    }

    /** @test */
    public function updateEmailDoesNothingIfLacksDummyPrefix()
    {
        $request = \Mockery::spy(LTIRequest::class);
        $request->shouldReceive('has')->andReturn(true);
        $request->lis_person_contact_email_primary = $this->faker->email;

        $user = User::factory()->make();

        $result = $this->object->updateEmail($user, $request);

        $this->assertInstanceOf(User::class, $result, 'Returns user object');
        $this->assertEquals($user->email, $result->email);
        $this->assertNotEquals($result->email, $request->lis_person_contact_email_primary, "did not use email from request");

    }


    /** @test */
    public function updateEmailNoEmailInRequest()
    {
        $request = new LTIRequest();
        $expected = 'dog@wag';
        $user = User::factory()->make(['email' => $expected]);
        //call
        $result = $this->object->updateEmail($user, $request);
        //check
        $this->assertEquals($expected, $result->email, "No update when email not in request");
    }

}
