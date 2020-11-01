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

    public $motions = [
        ['content' => "RESOLVED that tacos be served at every meeting",
            'requires' => 0.5],
        ['content' => "That the proposed curriculum regarding the study of tacos, especially pertaining to their deliciousness, be approved",
            'requires' => 0.5],
        ['content' => "That the pending matter be tabled", 'requires' => 0.5],
        ['content' => "That the CSUN cats be invited to each Senate meeting", 'requires' => 0.5],
        ['content' => "That the call for the 3 pending questions be approved", 'requires' => 0.66],
        ['content' => "That the call for the previous question be approved", 'requires' => 0.66],
    ];

    public $descriptions = ["",
        "If this motion is approved, we will immediately vote on the next motion",
        "Please vote once or forever hold your peace",
        "This proposes a revision to the Bylaws which will be voted upon by the whole Faculty"];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $m = $this->faker->randomElement($this->motions);

//        $requires = $this->faker->randomElement(Motion::ALLOWED_VOTE_REQUIREMENTS);

//        $content = $this->faker->randomElement($this->motions);

        $description = $this->faker->randomElement($this->descriptions);

        return [

            /** The thing being voted upon */
            'content' => $m['content'],

            'description' => $description,

            'requires' => $m['requires']

        ];
    }


    /**
     * Returns a motion which requires a majority
     */
    public function majority()
    {
        return $this->state(function (array $attributes) {
            return [
                'requires' => 0.5,
            ];
        });
    }


    /**
     * Returns a motion which requires 2/3
     */
    public function twoThirds()
    {
        return $this->state(function (array $attributes) {
            return [
                'requires' => 0.66,
            ];
        });
    }

    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_complete' => true,
            ];
        });
    }
}
