<?php


namespace Database\Factories;

use App\Models\LTIConsumer;
use App\Models\Meeting;
use App\Models\ResourceLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceLinkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ResourceLink::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //create a new activity and link it via the id
        $meeting = Meeting::factory()->create();
        $consumer = LTIConsumer::factory()->create();
        return [
            'meeting_id' => $meeting->id,
            'description' => $this->faker->sentence,

            'lti_consumer_id' => $consumer->id
        ];

    }
}
