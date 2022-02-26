<?php

namespace Database\Seeders;

use App\Models\Election\Candidate;
use App\Models\Meeting;
use App\Models\Motion;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CSUNStandingCommitteeWithCandidatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->faker = Factory::create();

        $election = Meeting::factory()->election()->create(CSUNStandingCommitteeSeeder::$electionProps);

        foreach(CSUNStandingCommitteeSeeder::$multiwinnerOffices as $office){
            $office['meeting_id'] = $election->id;
           $m = Motion::factory()
                ->electedOfficeSingleChoice()
                ->create($office);

            $numCandidates = $this->faker->numberBetween($office->max_winners, 2 * $office->max_winners);
            Candidate::factory()->count($numCandidates)->create(['motion_id' => $m->id]);

        }


        $this->command->line("\n Fake CSUN Standing Committee election (with candidates): {$election->id}");

    }
}
