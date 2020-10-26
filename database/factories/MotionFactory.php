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

    public $motionTexts = ["RESOLVED that tacos be served at every meeting",
        "That the proposed curriculum regarding the study of tacos, especially pertaining to their deliciousness, be approved",
        "That the pending matter be tabled",
        "That the CSUN cats be invited to each Senate meeting",
        "That the call for the 3 pending questions be approved",
        "That the call for the previous question be approved"
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

        $requires = $this->faker->randomElement(Motion::ALLOWED_VOTE_REQUIREMENTS);

        $content = $this->faker->randomElement($this->motionTexts);

        $description = $this->faker->randomElement($this->descriptions);

        return [

            /** The thing being voted upon */
            'content' => $content,

            'description' => $description,

            'requires' => $requires

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


}
