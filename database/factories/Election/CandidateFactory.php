<?php

namespace Database\Factories\Election;

use App\Models\Election\Candidate;
use App\Models\Election\Person;
use App\Models\Motion;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Candidate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $motion = Motion::factory()->create();
        $person = Person::factory()->create();

        return [
            'motion_id' => $motion->id,
            'person_id' => $person->id
            //
        ];
    }

    public function writeIn(){
        return $this->state(function (array $attributes) {
            return [
                'is_write_in' => true
            ];
        });
    }
}
