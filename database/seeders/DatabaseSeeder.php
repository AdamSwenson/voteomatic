<?php

namespace Database\Seeders;

use App\Models\LTIConsumer;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\ResourceLink;
use App\Models\User;
use App\Models\Vote;
use Database\Factories\ResourceLinkFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();
        Meeting::factory(2)->create();
        $motions = Motion::factory(3)->create();

        Vote::factory(['motion_id' => $motions[0]->id])->count(10)->create();

        $this->addDevCreds();

    }


    public function addDevCreds(){
        $meeting = Meeting::factory()->create();
        $consumer = LTIConsumer::factory([
            'consumer_key' => env('DEV_CONSUMER_KEY'),
            'secret_key' => env('DEV_SHARED_KEY')
            ])->create();

        ResourceLink::factory([
            'meeting_id' => $meeting->id,
            'resource_link_id' => env('DEV_RESOURCE_LINK_ID'),
            'lti_consumer_id' => $consumer->id
        ])->create();


    }
}
