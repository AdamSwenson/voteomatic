<?php

namespace Database\Factories;

use App\Models\Motion;
use App\Models\RecordedVoteRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecordedVoteRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RecordedVoteRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $motion = Motion::factory()->create();
        $user = User::factory()->make();

        return [
            'motion_id' => $motion->id,
            'user_id' => $user->id
        ];
    }
}
