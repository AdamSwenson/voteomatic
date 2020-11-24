<?php

namespace Database\Seeders;

use App\Models\LTIConsumer;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\ResourceLink;
use App\Models\User;
use App\Models\Vote;
use Database\Seeders\AssignmentSeeder;
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
        //this will be non-admin users
        $num_users = 10;

        $meetings = Meeting::factory(2)->create();
//try {
    //make an admin user
    $adminUser = User::factory()->administrator()->create();

    //make a regular user with known email and password
    $devUser = User::factory()->regUser()->create();
//}catch(QueryException)
        foreach ($meetings as $meeting) {
            $meeting->users()->attach($adminUser);
            $meeting->users()->attach($devUser);

            for ($i = 0; $i < $num_users; $i++) {
                $user = User::factory()->create();
                $meeting->users()->attach($user);
            }

            $meeting->save();

            $motions = Motion::factory(['meeting_id' => $meeting->id])
                ->count(5)
                ->create();

            foreach ($motions as $motion){
                for ($i = 0; $i < $num_users; $i++) {
                    Vote::factory(['motion_id' => $motion->id])
                        ->count(10)
                        ->create();
                }
            }

        }

        $this->call([
            AdminUserSeeder::class,
            LTIDevCredsSeeder::class,
            AssignmentSeeder::class,
            FakeFullMeetingSeeder::class
        ]);
//        $this->addDevCreds($meetings[0]);

    }

//
//    public function addDevCreds($meeting)
//    {
////        $meeting = Meeting::factory()->create();
//        $consumer = LTIConsumer::factory([
//            'consumer_key' => env('DEV_CONSUMER_KEY'),
//            'secret_key' => env('DEV_SHARED_KEY')
//        ])->create();
//
//        ResourceLink::factory([
//            'meeting_id' => $meeting->id,
//            'resource_link_id' => env('DEV_RESOURCE_LINK_ID'),
//            'lti_consumer_id' => $consumer->id
//        ])->create();
//
//
//    }
}
