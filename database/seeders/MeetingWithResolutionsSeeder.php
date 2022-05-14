<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class MeetingWithResolutionsSeeder extends Seeder
{

    public function makeResolution(Meeting $meeting)
    {
        $numResolves = $this->faker->numberBetween(2, 8);

        $title = $this->faker->sentence;
        $body = '';
        $body .= "<h4 class='rezzieTitle'>" . $title . "</h4>";

        for ($i = 0; $i <= $numResolves; $i++) {
            $body .= "<p class='resolvedClause'>RESOLVED {$this->faker->paragraph}; and be it further </p>";
        }

        $m = Motion::factory()->resolution()->create(
            ['meeting_id' => $meeting->id,
                'content' => $body,
                'seconded' => true,
//                'info' => [
//                    'title' => $title,
////                        'resolutionIdentifier' => $this->faker->randomNumber(4),
//                    'groupId' => null
//                ],
//                'is_resolution' => true,
            ]
        );

        $m->info['groupId'] = $m->id;

        $m->save();
        return $m;
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->faker = Factory::create();

        $realUsers = User::all();

        $meeting = Meeting::factory()->create();
        foreach ($realUsers as $user) {
            $meeting->addUserToMeeting($user);
        }
        $adminUser = User::where('email', env('DEV_USER_ADMIN_EMAIL'))->first();
        $meeting->setOwner($adminUser);

        $numResolutions = $this->faker->numberBetween(3, 8);

        for ($i = 0; $i <= $numResolutions; $i++) {
            $m = $this->makeResolution($meeting);
            if ($i === $numResolutions ) {
                $m->is_current = true;
                $m->save();
            }
        }

        $this->command->line("\n Meeting with resolutions: {$meeting->id}");


        //
    }
}
