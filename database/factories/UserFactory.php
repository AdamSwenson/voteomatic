<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'user_id_hash' => $this->faker->sha1,
            'sis_id' => $this->faker->randomNumber(6),

            'email' => $this->faker->sha1 . $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),

          'password' => Str::random(30),
            'remember_token' => Str::random(10),
        ];

    }

    /**
     * Someone who may create and over see votes on motions
     */
    public function administrator(){
        return $this->state(function (array $attributes) {
            return [
                'is_admin' => true,

            ];
        });

    }

    /**
     * Someone who may create and over see votes on motions
     */
    public function chair(){
        return $this->state(function (array $attributes) {
            return [
                'is_admin' => true,
            ];
        });

    }


    /**
     * Creates a non chair user.
     * Normally this won't be needed, since
     * it is (at least at present) the default factory.
     * But it may be useful elsewhere to be able to be explicit
     * about what user is being created.
     *
     * @return UserFactory
     */
    public function regUser(){
        return $this->state(function (array $attributes) {
            return [
                'is_admin' => false,
            ];
        });

    }
}
