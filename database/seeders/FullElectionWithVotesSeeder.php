<?php

namespace Database\Seeders;

use App\Models\Election\Candidate;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\Vote;
use Illuminate\Database\Seeder;
use Faker\Factory;

class FullElectionWithVotesSeeder extends Seeder
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

            $numCandidates = $this->faker->numberBetween(1, self::MAX_CANDIDATES);
            $candidates = Candidate::factory()->count($numCandidates)->create(['motion_id' => $m->id]);

            //Add votes
            $winner = $this->faker->randomElement($candidates);
            $winningVotes = $this->faker->numberBetween(10, 100);

            foreach ($candidates as $candidate) {
                if ($winner->is($candidate)) {
                    Vote::factory()->count($winningVotes)->create(['motion_id' => $m->id,
                        'candidate_id' => $candidate->id]);
                } else {
                    $loserVotes = $this->faker->numberBetween(0, $winningVotes - 1);
                    Vote::factory()
                        ->count($loserVotes)
                        ->create([
                            'motion_id' => $m->id,
                            'candidate_id' => $candidate->id
                        ]);

                }


            }
        }

        $this->command->line("\n Election with votes: {$election->id}");

    }
}
