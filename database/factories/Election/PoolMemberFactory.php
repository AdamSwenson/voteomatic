<?php

namespace Database\Factories\Election;

use App\Models\Election\Person;
use App\Models\Election\PoolMember;

use App\Models\Motion;
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
        $motion = Motion::factory()->create();
        $person = Person::factory()->create();

        return [
            'motion_id' => $motion->id,
            'person_id' => $person->id
            //
        ];
//            'first_name' => $this->faker->firstName,
//            'last_name' => $this->faker->lastName,
//            'info' => $this->faker->sentence
//        ];
    }
}
