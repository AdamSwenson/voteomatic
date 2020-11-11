<?php

namespace Database\Seeders;

use App\Models\LTIConsumer;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\ResourceLink;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;

/**
 * Class ProductionSeeder
 *
 * This seeder runs on the production server to
 * populate it with the needed sample entries.
 *
 *
 * @package Database\Seeders
 */
class ProductionTestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //we make 1 admin user since
        //everyone else logging in will be
        //non-admins
        //todo Figure out how to handle this

        //make an admin user
        $user = User::factory()->administrator()->create();

        //we are only populating this to demonstrate the meeting list
        //page. There will be no canvas resource link to the second meeting
        $meetings = Meeting::factory(2)->create();

        //we only create a resource link to the first meeting
        $this->addDevLTICreds($meetings[0]);

        foreach ($meetings as $meeting) {
            $meeting->users()->attach($user);
            $meeting->save();

            $motions = Motion::factory(['meeting_id' => $meeting->id])
                ->count(5)
                ->create();

        }


    }


    /**
     * Adds consumer key,  secret key, and resource link
     * for running with live link to canvas.
     *
     * @param $meeting
     */
    public function addDevLTICreds($meeting)
    {
//        $meeting = Meeting::factory()->create();
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
