<?php

namespace Database\Seeders;

use App\Models\Election\Candidate;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use App\Models\Vote;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CSUNStandingCommitteeWithVotesSeeder extends Seeder
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

        foreach (CSUNStandingCommitteeSeeder::$multiwinnerOffices as $office) {
            $office['meeting_id'] = $election->id;
            $m = Motion::factory()
                ->electedOffice()
                ->create($office);

            $numCandidates = $this->faker->numberBetween($office['max_winners'], 2 * $office['max_winners']);
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
        $adminUser = User::where('email', env('DEV_USER_ADMIN_EMAIL'))->first();
        $election->setOwner($adminUser);


        $this->command->line("\n Fake CSUN Standing Committee election (with votes): {$election->id}");

    }
}
