<?php

namespace Database\Factories;

use App\Models\Meeting;
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
            'date' => $this->faker->dateTimeThisCentury
        ];
    }
}
