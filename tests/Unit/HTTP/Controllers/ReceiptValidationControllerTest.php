<?php

namespace Tests\Unit\HTTP\Controllers;

use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use App\Models\Vote;
use Tests\TestCase;

//use PHPUnit\Framework\TestCase;


class ReceiptValidationControllerTest extends TestCase
{

    public $user;

    public function setUp():void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->meeting = Meeting::factory()->create();
        $this->meeting->addUserToMeeting($this->user);

        $this->url = 'validation';
    }





    /** @test */
    public function validateReceiptYay()
    {
        $vote = Vote::factory()->create(['is_yay' => true]);

        $data = ['receipt' => $vote->receipt];

        //call
        $response = $this->actingAs($this->user)
            ->post($this->url, $data);

        $response->assertStatus(200);
        $response->assertJson($vote->toArray());

    }


    /** @test */
    public function validateReceiptNay()
    {
        $vote = Vote::factory()->create(['is_yay' => false]);

        $data = ['receipt' => $vote->receipt];

        //call
        $response = $this->actingAs($this->user)
            ->post($this->url, $data);

        $response->assertStatus(200);
        $response->assertJson($vote->toArray());


        $data = ['receipt' => ''];

        //call
        $response = $this->actingAs($this->user)
            ->post($this->url, $data);

        $response->assertStatus(404);

    }



    /** @test */
    public function validateReceiptInvalidReceipt()
    {
        $vote = Vote::factory()->create(['is_yay' => false]);

        $badReceipt = $this->faker->sha256;

        $data = ['receipt' => $badReceipt];


        //call
        $response = $this->actingAs($this->user)
            ->post($this->url, $data);

        $response->assertStatus(404);
    }


    /** @test */
    public function validateReceiptEmptyString()
    {

        $data = ['receipt' => ""];

        //call
        $response = $this->actingAs($this->user)
            ->post($this->url, $data);

        $response->assertStatus(404);
    }

    /** @test */
    public function validateReceiptReplicateFebSenateBug()
    {
        //This replicates the bug encountered in the feb 26 senate meeting

        //call
        $data = ['receipt' => ""];

        $response = $this->actingAs($this->user)
            ->post($this->url, $data);

        $response->assertStatus(404);

        //so far so good, now for the error

        //prep
        //NB, have to create with null rather than empty string
        //because the request will parse the empty string to null
        $vote = Vote::factory()->create(['receipt' => null, 'is_yay' => true]);
        $data = ['receipt' => ''];

        //call
        $response = $this->actingAs($this->user)
            ->post($this->url, $data);

        $response->assertStatus(200);

    }


}
