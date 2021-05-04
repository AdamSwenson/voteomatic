<?php

namespace App\Repositories;

use App\Http\Requests\LTIRequest;
use App\Models\Meeting;
use App\Models\User;
use App\Repositories\UserRepository;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{

    public function setUp():void
    {
        parent::setUp();

        $this->object = new UserRepository();

    }

    public function testGetUserFromRequest()
    {
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

}
