<?php
namespace Database\Factories;

use App\Models\LTIConsumer;
use Illuminate\Database\Eloquent\Factories\Factory;

class LTIConsumerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LTIConsumer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'name' => $this->faker->company,
            'consumer_key' => $this->faker->sha1,
            'secret_key' => $this->faker->sha1,
        ];
    }
}
