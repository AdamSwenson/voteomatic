<?php

namespace Database\Factories;

use App\Activity;
use App\Assignment;
use App\Models\Model;
use App\Motion;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Assignment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        //will fail if no other assignments around, but oh well
        $parentAssignment = Assignment::all();
        if (sizeof($parentAssignment) > 0) {
            $parentAssignment = $parentAssignment->random()->first();
        }else{
            $parentAssignment = Assignment::create();
        }

        //factory(Motion::class)->create();
        $assignedMotion = Motion::factory()->create();

        $meeting  = Meeting::factory()->create();

        return [
            'parent_id' => $parentAssignment->id,
            'position' =>  $this->faker->randomDigitNotNull,
            'meeting_id' => $meeting->id,
            'motion_id' => $assignedMotion->id
            //
        ];
        }
}
