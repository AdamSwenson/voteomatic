<?php

namespace Tests\Repositories;

use App\Models\LTIConsumer;
use App\Models\Meeting;
use App\Models\ResourceLink;
use App\Repositories\LTIRepository;
use Illuminate\Support\Str;
use Tests\TestCase;


class LTIRepositoryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->object = new LTIRepository();
    }

    public function testGenerateConsumerKey()
    {
        $result = LTIRepository::generateConsumerKey();
        $this->assertEquals(LTIRepository::KEY_LENGTH, Str::of($result)->length(), "Key is correct length");
    }

    public function testGenerateSecretKey()
    {
        $result = LTIRepository::generateSecretKey();

        $this->assertEquals(LTIRepository::KEY_LENGTH, Str::of($result)->length(), "Key is correct length");

    }


    /** @test */
    public function createLTIConsumer()
    {
        $name = $this->faker->company;

        $result = $this->object->createLTIConsumer($name);

        $this->assertInstanceOf(LTIConsumer::class, $result);
        $this->assertEquals(LTIRepository::KEY_LENGTH, Str::of($result->consumer_key)->length(), "Consumer key is correct length");
        $this->assertEquals(LTIRepository::KEY_LENGTH, Str::of($result->secret_key)->length(), "Secret key is correct length");

    }

    /** @test */
    public function createResourceLink()
    {
        $description = $this->faker->paragraph;
        $meeting = Meeting::factory()->create();
        $consumer = LTIConsumer::factory()->create();

        $result = $this->object->createResourceLink($consumer, $meeting, $description);

        $this->assertInstanceOf(ResourceLink::class, $result);
        $this->assertEquals(LTIRepository::KEY_LENGTH, Str::of($result->resource_link_id)->length(), "Consumer key is correct length");
        $this->assertEquals($meeting->id, $result->meeting_id);
        $this->assertEquals($consumer->id, $result->lti_consumer_id);
        $this->assertEquals($description, $result->description);


    }

}

