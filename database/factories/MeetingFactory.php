<?php

namespace Database\Factories;

use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeetingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Meeting::class;

    public $names = ['Senate Meeting',
        'Annual Plenary Session',
        'Taco Superfan Club Weekly Congress',
        'Assembly of Notables',
        'Assembly of the Damned',
        'Parliament of Owls',
        'Destruction of Wildcats',
        'Board of Trustees meeting'];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement($this->names),
            'date' => Carbon::now() //$this->faker->dateTimeThisCentury
        ];
    }

    public function election()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => $this->faker->randomElement($this->names) . " ELECTION!",
                'phase' => 'setup',
                'is_election' => true,
                'is_voting_available' => true,
                'info' => ['is_results_available' => false]
            ];
        });
    }

    public function electionSetupPhase()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => $this->faker->randomElement($this->names) . " ELECTION!",
                'is_election' => true,
                'phase' => 'setup',
                'is_voting_available' => false,
                'is_complete' => false,
                'info' => ['is_results_available' => false]
            ];
        });
    }

    public function electionNominationsPhase()
    {
        return $this->state(function (array $attributes) {
            return [
                //dev todo
                'name' => $this->faker->randomElement($this->names) . " ELECTION!",
                'is_election' => true,
                'phase' => 'nominations',
                'is_voting_available' => false,
                'is_complete' => false,
                'info' => ['is_results_available' => false]
            ];
        });
    }


    public function electionVotingPhase()
    {
        return $this->state(function (array $attributes) {
            return [

                'name' => $this->faker->randomElement($this->names) . " ELECTION!",
                'is_election' => true,
                'phase' => 'voting',
                'is_voting_available' => true,
                'is_complete' => false,
                'info' => ['is_results_available' => false]
            ];
        });
    }


    public function electionClosedPhase()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => $this->faker->randomElement($this->names) . " ELECTION!",
                'is_election' => true,
                'phase' => 'closed',
                'is_voting_available' => false,
                'is_complete' => true,
                'info' => ['is_results_available' => false]
            ];
        });
    }


    public function electionResultsPhase()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => $this->faker->randomElement($this->names) . " ELECTION!",
                'is_election' => true,
                'phase' => 'results',
                'is_voting_available' => false,
                'is_complete' => true,
                'info' => ['is_results_available' => true]
            ];
        });
    }

}
