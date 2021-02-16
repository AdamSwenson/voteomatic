<?php

namespace Database\Factories\Election;

use App\Models\Election\Candidate;
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
        return [
            'name' => $this->faker->name,
            'info' => $this->faker->sentence
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
