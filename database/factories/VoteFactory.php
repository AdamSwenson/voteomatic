<?php

namespace Database\Factories;

use App\Models\Motion;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();
        $motion = Motion::factory()->create();
        $is_yay = ($this->faker->randomNumber() % 2) == 0;


        $receipt = $this->faker->sha256();
        return [
                        'user_id' => $user->id,
                        'motion_id' => $motion->id,
                        'is_yay' => $is_yay
            //
        ];
    }

    public function abstention(){
        return $this->state(function (array $attributes) {
            return [
                'is_yay' => null,
            ];
        });

    }

    /**
     * Returns a yay vote
     */
    public function affirmative(){
        return $this->state(function (array $attributes) {
            return [
                'is_yay' => true,
            ];
        });
    }

    /**
     * Returns a nay vote
     */
    public function negative(){
        return $this->state(function (array $attributes) {
            return [
                'is_yay' => false,
            ];
        });
    }
}
