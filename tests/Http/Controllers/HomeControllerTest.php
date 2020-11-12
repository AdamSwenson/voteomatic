<?php

namespace Tests\Http\Controllers;

use App\Http\Controllers\HomeController;
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
