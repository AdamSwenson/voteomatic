<?php

namespace Tests\helpers;

use App\Models\Election\Candidate;
use App\Models\Meeting;
use App\Models\Motion;
use App\Repositories\Election\CandidateRepository;
use App\Repositories\Election\IElectionRepository;
use App\Repositories\IMeetingRepository;
use Faker\Factory;

class FakeFullElectionMaker
{
    const NUM_SINGLE_CHOICE_OFFICES = 5;
    const NUM_MULTIPLE_CHOICE_OFFICES = 5;
    const NUM_PROPOSITIONS = 2;

    /**
     * Creates full fake election.
     *
     * @return void
     */
    public static function make()
    {
        $faker = Factory::create();
        $electionRepo = app()->make(IElectionRepository::class);
        $meetingRepo = app()->make(IMeetingRepository::class);
        $candidateRepo = app()->make(CandidateRepository::class);

        $election = Meeting::factory()->election()->create();

        for ($i = 0; $i < self::NUM_SINGLE_CHOICE_OFFICES; $i++) {
            $office = $electionRepo->addOfficeToElection($election, $faker->title, $faker->text);
            Candidate::factory()->count($faker->randomDigit)->create(['motion_id' => $office->id]);
        }

        for ($i = 0; $i < self::NUM_MULTIPLE_CHOICE_OFFICES; $i++) {
            $office = $electionRepo->addOfficeToElection($election, $faker->title, $faker->text, $faker->randomDigit);
            Candidate::factory()->count($faker->randomDigit)->create(['motion_id' => $office->id]);
        }

        for ($i = 0; $i < self::NUM_PROPOSITIONS; $i++) {
            Motion::factory()->proposition()->create(['meeting_id' => $election->id]);
         }
return $election;
}}
