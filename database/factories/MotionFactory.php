<?php

namespace Database\Factories;

use App\Models\Motion;
use Illuminate\Database\Eloquent\Factories\Factory;

class MotionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Motion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $requires = $this->faker->randomElement(Motion::ALLOWED_VOTE_REQUIREMENTS);

        return [

            /** The thing being voted upon */
            'content' => $this->faker->text(),

            'description' => $this->faker->text(),

            'requires' => $requires

        ];
    }


    /**
     * Returns a motion which requires a majority
     */
    public function majority(){
        return $this->state(function (array $attributes) {
            return [
                'requires' => 0.5,
            ];
        });
    }


    /**
     * Returns a motion which requires 2/3
     */
    public function twoThirds(){
        return $this->state(function (array $attributes) {
            return [
                'requires' => 0.75,
            ];
        });
    }



}
