<?php

namespace Database\Factories\Election;

use App\Models\Election\PoolMember;

use Illuminate\Database\Eloquent\Factories\Factory;

class PoolMemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PoolMember::class;

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
            'info' => $this->faker->sentence
        ];
    }
}
