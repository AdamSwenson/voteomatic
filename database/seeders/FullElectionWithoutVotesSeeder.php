<?php

namespace Database\Seeders;

use App\Models\Election\Candidate;
use App\Models\Meeting;
use App\Models\Motion;
use Illuminate\Database\Seeder;
use Faker\Factory;

class FullElectionWithoutVotesSeeder extends Seeder
{
    const NUMBER_OFFICES = 5;

    const MAX_CANDIDATES = 5;
    /**
     * @var \Faker\Generator
     */
    public $faker;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->faker = Factory::create();

        $election = Meeting::factory()->election()->create();

        for ($i = 0; $i < self::NUMBER_OFFICES; $i++) {

            $m = Motion::factory()
                ->electedOfficeSingleChoice()
                ->create([
                    'meeting_id' => $election->id,
                ]);

            //Randomly have multiple winner elections
            if($this->faker->boolean){
                $numWinners = $this->faker->numberBetween(2, self::MAX_CANDIDATES);
                $m->max_winners = $numWinners;
                $m->save();
            }

            $numCandidates = $this->faker->numberBetween(1, self::MAX_CANDIDATES);
            $candidates = Candidate::factory()->count($numCandidates)->create(['motion_id' => $m->id]);

        }

        $m2 = Motion::factory()
            ->proposition()
            ->create([
                'meeting_id' => $election->id,
            ]);

        $this->command->line("\n Election with no votes: {$election->id}");

    }
}
