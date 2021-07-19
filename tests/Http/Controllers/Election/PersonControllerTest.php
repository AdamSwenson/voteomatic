<?php

namespace Tests\Http\Controllers\Election;

use App\Http\Controllers\Election\PersonController;

//use PHPUnit\Framework\TestCase;
use App\Models\Election\Person;
use App\Models\User;
use Tests\TestCase;

class PersonControllerTest extends TestCase
{

    /**
     * @var string
     */
    private $url;
    private $person;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    private $rando;
    private $admin;

    public function setUp(): void
    {
        parent::setUp();
$this->url  = 'election/people';

$this->rando = User::factory()->create();
$this->admin = User::factory()->administrator()->create();

$this->person = Person::factory()->create();
    }

    /** @test */
    public function destroy()
    {
$url = $this->url . '/' . $this->person->id;

$response = $this->actingAs($this->admin)->delete($url);

$response->assertSuccessful();

    }

    /** @test */
    public function destroyRejectsNonAdmin()
    {
        $url = $this->url . '/' . $this->person->id;

        $response = $this->actingAs($this->rando)->delete($url);

        $response->assertStatus(403);
    }

    /** @test */
    public function storeAllowsRandos()
    {
        $p = Person::factory()->create();

        $response = $this->actingAs($this->rando)->post($this->url, $p->toArray());

        $response->assertSuccessful();
    }

    /** @test */
    public function showAllowsRandos()
    {
        $url = $this->url . '/' . $this->person->id;

        $response = $this->actingAs($this->rando)->get($url);

        $response->assertSuccessful();
//        $response->assertJson($this->person->toArray());
    }

    /** @test */
    public function updateAllowsRandos()
    {
        $url = $this->url . '/' . $this->person->id;

        $p = Person::factory()->create();

        $response = $this->actingAs($this->rando)->patch($url, $p->toArray());

        $response->assertSuccessful();

    }

}
