<?php

namespace Tests\Http\Controllers;

use App\Http\Controllers\HomeController;
use App\Models\Meeting;
use App\Models\User;
use Tests\TestCase;


class HomeControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->url = '/home';

    }

    public function testMeetingIndex()
    {
        $meeting = Meeting::factory()->create();
        $url = $this->url . '/' . $meeting->id;


        $expectedData = [
            'meeting_id' => $meeting->id,

            'isAdmin' => $this->user->is_admin,
        ];

        //call
        $response = $this->actingAs($this->user)
            ->get($url);

        //check
        $this->assertEquals(200, $response->status(), "Expected 200 created response code returned");
        $response->assertViewIs('main');
        $response->assertViewHas('data', $expectedData);


    }

    public function testIndex()
    {
        //call
        $response = $this->actingAs($this->user)
            ->get($this->url);

        //check
        $this->assertEquals(200, $response->status(), "Expected 200 created response code returned");
        $response->assertViewIs('home');
    }
}
