<?php

namespace Database\Seeders;

use App\Models\Election\Candidate;
use App\Models\Meeting;
use App\Models\Motion;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CSUNElectionSeederWithCandidates extends Seeder
{
    const MAX_CANDIDATES = 5;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->faker = Factory::create();

        $election = Meeting::factory()->election()->create(CSUNElectionSeeder::$electionProps);

        foreach(CSUNElectionSeeder::$singleChoiceOffices as $office){
            $office['meeting_id'] = $election->id;
            $m = Motion::factory()
                ->electedOfficeSingleChoice()
                ->create($office);

            $numCandidates = $this->faker->numberBetween(1, self::MAX_CANDIDATES);
            Candidate::factory()->count($numCandidates)->create(['motion_id' => $m->id]);
        }

        foreach(CSUNElectionSeeder::$multiwinnerOffices as $office){
            $office['meeting_id'] = $election->id;
            $m = Motion::factory()
                ->electedOffice()
                ->create($office);

            $numCandidates = $this->faker->numberBetween(1, self::MAX_CANDIDATES);
            Candidate::factory()->count($numCandidates)->create(['motion_id' => $m->id]);
        }

        foreach (CSUNElectionSeeder::$propositions as $p){
            $p['meeting_id'] = $election->id;
            Motion::factory()->proposition()->create($p);
        }

        $this->command->line("\n Fake CSUN election (fake candidates): {$election->id}");

    }
}
