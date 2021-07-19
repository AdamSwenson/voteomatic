<?php

namespace Tests\Http\Controllers\Election;

use App\Http\Controllers\Election\OfficeController;

//use PHPUnit\Framework\TestCase;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use App\Repositories\Election\IElectionRepository;
use Tests\TestCase;

class OfficeControllerTest extends TestCase
{

    private $owner;
    private $election;
    /**
     * @var string
     */
    private $url;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    private $regularUserMember;
    private $office;

    public function setUp(): void
    {

        parent::setUp();
        $this->owner = User::factory()->administrator()->create();

        $this->election = Meeting::factory()->election()->create();
        $this->election->setOwner($this->owner);

        $this->url = "offices";

        $this->regularUserMember = User::factory()->create();
        $this->election->addUserToMeeting($this->regularUserMember);

        $this->office = Motion::factory()->electedOffice()->create(['meeting_id' => $this->election->id]);

    }


    /** @test */
    public function destroy()
    {
        $url = $this->url . '/' . $this->office->id;

        $response = $this->actingAs($this->owner)->delete($url);

        $response->assertSuccessful();
    }


    /** @test */
    public function destroyDeniesNonOwner()
    {
        $url = $this->url . '/' . $this->office->id;

        $response = $this->actingAs($this->regularUserMember)->delete($url);

        $response->assertStatus(403);
    }



    /** @test */
    public function showAllowsOwner()
    {
        $url = $this->url . '/' . $this->office->id;

        $response = $this->actingAs($this->owner)->get($url);

        $response->assertSuccessful();
        $response->assertJson($this->office->toArray());
    }

    /** @test */
    public function showAllowsRegularMember()
    {
        $url = $this->url . '/' . $this->office->id;

        $response = $this->actingAs($this->owner)->get($url);

        $response->assertSuccessful();
        $response->assertJson($this->office->toArray());
    }

    /** @test */
    public function showDeniesNonMember()
    {
        $url = $this->url . '/' . $this->office->id;

        $rando = User::factory()->create();

        $response = $this->actingAs($rando)->get($url);

        $response->assertStatus(403);
    }

    /** @test */
    public function store()
    {
        $this->mock(IElectionRepository::class)
            ->shouldReceive('addOfficeToElection')
            ->andReturn($this->office);
        $data = ['meetingId' => $this->election->id];

        $response = $this->actingAs($this->owner)->post($this->url, $data);

        $response->assertSuccessful();
        $response->assertJson($this->office->toArray());
    }

    /** @test */
    public function storeDeniesNonOwner()
    {
        $data = ['meetingId' => $this->election->id];

        $response = $this->actingAs($this->regularUserMember)->post($this->url, $data);

        $response->assertStatus(403);
    }


    /** @test */
    public function update()
    {
        $o = Motion::factory()->electedOffice()->create();
        $url = $this->url . '/' . $this->office->id;

        $response = $this->actingAs($this->owner)->patch($url, $o->toArray());

        $response->assertSuccessful();
        $response->assertJsonFragment(['description' => $o->description, 'content' => $o->content]);

    }

    /** @test */
    public function updateDeniesNonOwner()
    {
        $o = Motion::factory()->electedOffice()->create();
        $url = $this->url . '/' . $this->office->id;

        $response = $this->actingAs($this->regularUserMember)->patch($url, $o->toArray());

        $response->assertStatus(403);
    }


}
