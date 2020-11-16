<?php

namespace Database\Seeders;

use App\Models\LTIConsumer;
use App\Models\Meeting;
use App\Models\ResourceLink;
use Illuminate\Database\Seeder;

class LTIDevCredsSeeder extends Seeder
{
    /**
     * Creates the keys used in the LTI authentication
     * process on the first meeting in the database.
     *
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $meetings = Meeting::all();
        if (! isset($meetings)) {
            $meeting = Meeting::factory()->create();
        }else {
            $meeting = $meetings[0];
        }

        $consumer = LTIConsumer::factory([
            'consumer_key' => env('DEV_CONSUMER_KEY'),
            'secret_key' => env('DEV_SHARED_KEY')
        ])->create();

        ResourceLink::factory([
            'meeting_id' => $meeting->id,
            'resource_link_id' => env('DEV_RESOURCE_LINK_ID'),
            'lti_consumer_id' => $consumer->id
        ])->create();



        //
    }
}
