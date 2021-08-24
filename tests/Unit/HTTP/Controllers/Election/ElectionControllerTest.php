<?php

namespace Tests\Http\Controllers\Election;

use App\Http\Controllers\Election\ElectionController;

//use PHPUnit\Framework\TestCase;
use App\Models\Meeting;
use App\Models\User;
use App\Repositories\IMeetingRepository;
use Tests\TestCase;

class ElectionControllerTest extends TestCase
{

    public $owner;
    public $election;
    public $adminUser;
    public $regularUser;
    /**
     * @var string
     */
    public $url;
    public $elections;

    public function setUp(): void
    {
        parent::setUp();
        $this->elections = Meeting::factory()->election()->count(3)->create();
        $this->election = $this->elections[0]; //Meeting::factory()->election()->create();

        $this->adminUser = User::factory()->administrator()->create();

        $this->regularUser = User::factory()->create();

        $this->owner = User::factory()->administrator()->create();
        $this->election->setOwner($this->owner);

        $this->url = 'elections';

    }

    /** @test */
    public function showAllowsOwner()
    {
        $url = $this->url . '/' . $this->election->id;
        $response = $this->actingAs($this->owner)->get($url);

        //check
        $response->assertSuccessful();
        $response->assertJson($this->election->toArray());
    }

    /** @test */
    public function showAllowsRegularUser()
    {
        $url = $this->url . '/' . $this->election->id;
        $response = $this->actingAs($this->regularUser)->get($url);

        //check
        $response->assertSuccessful();
        $response->assertJson($this->election->toArray());
    }


    /** @test */
    public function index()
    {
        $response = $this->actingAs($this->owner)->get($this->url);

        //check
        $response->assertSuccessful();
        $response->assertJson($this->elections->toArray());
    }

    /** @test */
    public function indexDeniesNonAdmin()
    {
        $response = $this->actingAs($this->regularUser)->get($this->url);

        //check
        $response->assertStatus(403);
    }


    /** @test */
    public function store()
    {
        $election = Meeting::factory()->election()->create();
        $this->mock(IMeetingRepository::class)
            ->shouldReceive('createElectionForUser')
            ->andReturn($this->election);

        $response = $this->actingAs($this->adminUser)->post($this->url, $election->toArray());

        //check
        $response->assertSuccessful();
        $response->assertJsonFragment($this->election->toArray());
    }

    /** @test */
    public function storeDeniesNonAdmin()
    {
        $this->mock(IMeetingRepository::class)
            ->shouldReceive('createElectionForUser')
            ->andReturn($this->election);

        $response = $this->actingAs($this->regularUser)->post($this->url, $this->election->toArray());

        //check
        $response->assertStatus(403);
    }

    /** @test */
    public function destroyAllowsOwner()
    {
        $url = $this->url . '/' . $this->election->id;
        $response = $this->actingAs($this->owner)->delete($url);

        //check
        $response->assertSuccessful();
    }

    /** @test */
    public function destroyDeniesNonOwner()
    {
        $url = $this->url . '/' . $this->election->id;
        $response = $this->actingAs($this->regularUser)->delete($url);

        //check
        $response->assertStatus(403);
    }

    /** @test */
    public function updateAllowsOwner()
    {
        $url = $this->url . '/' . $this->election->id;
        $election = Meeting::factory()->election()->create();
        $data = ['data' => ['name' => $election->name,
            'date' => $election->date,
        ]];

        $response = $this->actingAs($this->owner)->patch($url, $data);

        //check
        $response->assertSuccessful();
        $response->assertJsonFragment(['name' => $election->name,
            'date' => $election->date,
            'is_election' => true
        ]);
    }


    /** @test */
    public function updateDeniesNonOwner()
    {
        $url = $this->url . '/' . $this->election->id;

        $election = Meeting::factory()->election()->create();


        $response = $this->actingAs($this->regularUser)->patch($url, $election->toArray());

        //check
        $response->assertStatus(403);
    }
}
