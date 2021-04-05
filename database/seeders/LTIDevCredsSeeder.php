<?php

namespace Database\Seeders;

use App\Models\LTIConsumer;
use App\Models\Meeting;
use App\Models\ResourceLink;
use Illuminate\Database\Seeder;

class LTIDevCredsSeeder extends Seeder
{

    public $consumerName = 'Development consumer';

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
        //We don't want multiple entries
        if(! is_null(LTIConsumer::where('name', $this->consumerName)->first())) {
            return true;
        }


        $meeting = Meeting::find(1);

        if(is_null($meeting)) {
            $meeting = Meeting::factory()->create();
        }


        $consumer = LTIConsumer::create([
                'name' => $this->consumerName,
                'consumer_key' => env('DEV_CONSUMER_KEY'),
                'secret_key' => env('DEV_SHARED_KEY')
            ])->create();


        ResourceLink::create([
            'meeting_id' => $meeting->id,
            'resource_link_id' => env('DEV_RESOURCE_LINK_ID'),
            'lti_consumer_id' => $consumer->id
        ])->create();


    }
}
