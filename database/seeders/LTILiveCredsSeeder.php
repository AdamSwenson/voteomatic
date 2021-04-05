<?php

namespace Database\Seeders;

use App\Models\LTIConsumer;
use App\Models\Meeting;
use App\Models\ResourceLink;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


/**
 * Creates the credentials for one LTI access
 *
 *
 * Class LTILiveCredsSeeder
 * @package Database\Seeders
 */
class LTILiveCredsSeeder extends Seeder
{
    public $consumerName = 'Production consumer';


    /**
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

        if (is_null($meeting)) {
            $meeting = Meeting::create();
        }

        $consumer = LTIConsumer::create([
            'name' => $this->consumerName,
            'consumer_key' => Str::random(40),
            'secret_key' => Str::random(40)
        ])->create();


        $resourceLink = ResourceLink::create([
            'meeting_id' => $meeting->id,
            'resource_link_id' => Str::random(40),
            'lti_consumer_id' => $consumer->id
        ])->create();


        echo "\n \n Created LTI credentials  \n";
        echo "\n Consumer key: {$consumer->consumer_key} \n";
        echo "\n Secret key: {$consumer->secret_key} \n";
        echo "\n Resource link: {$resourceLink->resource_link_id} \n";
        echo "\n Meeting id: {$meeting->id} \n";
        echo "\n \n";


    }
}
