<?php

namespace Database\Factories;

use App\Models\Meeting;
use App\Models\SettingStore;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingStoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SettingStore::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();
        $meeting = Meeting::factory()->create();
        return [
            'user_id' => $user->id,
            'meeting_id' => $meeting->id,
            'is_universal' => $this->faker->boolean,
            'applies_to_all_members' => $this->faker->boolean,

            'settings' => [
                'members_make_motions' => $this->faker->boolean,
                'members_second_motions' => $this->faker->boolean,
//                'public_view' => $this->faker->boolean,
                'show_vote_counts' => $this->faker->boolean,
                'use_broadcasting' => $this->faker->boolean,
            ]

        ];
    }

    public function election()
    {
        $user = User::factory()->create();
        $meeting = Meeting::factory()->create();
        return [
            'user_id' => $user->id,
            'meeting_id' => $meeting->id,
            'is_universal' => $this->faker->boolean,
            'applies_to_all_members' => $this->faker->boolean,

            'settings' => [
                'members_make_nominations' => $this->faker->boolean,
                'show_vote_counts' => $this->faker->boolean,
            ]

        ];
    }

    public function meetingMaster()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_meeting_master' => true
            ];
        });
    }
}
