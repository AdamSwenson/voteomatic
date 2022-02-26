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

/**
 * This seeds the database for development
 *
 *
 *
 * Class DatabaseSeeder
 * @package Database\Seeders
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Meeting::factory(2)->create();

        $this->call([
            AdminUserSeeder::class,
            RegularUserSeeder::class,
            LTIDevCredsSeeder::class,
            FakeFullMeetingSeeder::class,
            FullElectionWithoutVotesSeeder::class,
            FullElectionWithVotesSeeder::class,
            CSUNElectionSeeder::class,
            CSUNElectionSeederWithCandidates::class,
            CSUNStandingCommitteeSeeder::class,
            CSUNElectionSeederWithCandidates::class
        ]);

        $meetings = Meeting::all();

        $adminUser = User::where('email', env('DEV_USER_ADMIN_EMAIL'))->first();
        $users = [
            $adminUser,
            User::where('email', env('DEV_USER_REGULAR_EMAIL'))->first(),
        ];

        foreach ($meetings as $meeting) {
            foreach ($users as $user) {
                $meeting->addUserToMeeting($user);
            }
            $meeting->setOwner($adminUser);
        }

    }

}
