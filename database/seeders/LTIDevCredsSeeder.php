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

        $meeting = Meeting::find(1);

        if(is_null($meeting)) {
            $meeting = Meeting::factory()->create();
        }


        $consumer = LTIConsumer::factory([
                'name' => 'Development consumer',
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
