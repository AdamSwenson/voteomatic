<?php

namespace Database\Factories;

use App\Models\Motion;
use App\Repositories\MotionTemplateRepository;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

class MotionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Motion::class;

    public $motions = [
        ['content' => "RESOLVED that tacos be served at every meeting",
            'requires' => 0.5, 'type' => 'main', 'description' => ""],
        ['content' => "RESOLVED That the proposed curriculum regarding the study of tacos, especially pertaining to their deliciousness, be approved",
            'requires' => 0.5, 'type' => 'main'],

        ['content' => "That the pending matter be tabled",
            'requires' => 0.5,
            'type' => 'procedural-subsidiary'
        ],
        ['content' => "RESOLVED That the CSUN cats be invited to each Senate meeting",
            'requires' => 0.5,
            'type' => 'main'
        ],
        ['content' => "That the call for the 3 pending questions be approved",
            'requires' => 0.66,
            'type' => 'procedural-subsidiary',
            'description' => "If it is approved, all debate ends on the 3 pending motions and the body moves immediately votes on all 3 in succession. If it fails, debate continues on the pending motion"
        ],
        ['content' => "That the call for the previous question be approved",
            'requires' => 0.66,
            'type' => 'procedural-subsidiary',
            'description' => "If it is approved, all debate ends on the pending motion and the body moves immediately to a vote on the pending motion. If it fails, debate continues on the pending motion"
        ],
    ];

//    public $descriptions = ["",
//        "If this motion is approved, we will immediately vote on the next motion",
//        "Please vote once or forever hold your peace",
//        "This proposes a revision to the Bylaws which will be voted upon by the whole Faculty"];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $m = $this->faker->randomElement($this->motions);

//        $requires = $this->faker->randomElement(Motion::ALLOWED_VOTE_REQUIREMENTS);

//        $content = $this->faker->randomElement($this->motions);

//        $description = $this->faker->randomElement($this->descriptions);

        return [

            /** The thing being voted upon */
            'content' => $m['content'],

            'description' => isset($m['description']) ? $m['description'] : null,

            'requires' => $m['requires'],
            'type' => $m['type'],
            'seconded' => false,

            'author_id' => null,

        ];
    }

    public function amendment(){

        return $this->state(function (array $attributes) {
            $main = new Motion();
            return [
                'applies_to' => $main->id,
                'type' => 'amendment'
            ];
        });
    }


    /**
     * Returns a motion which requires a majority
     */
    public function majority()
    {
        return $this->state(function (array $attributes) {
            return [
                'requires' => 0.5,
            ];
        });
    }

//    public function passedMajority(){
//        Vote::factory()->create(['motion_id'])
//        return $this->state(function (array $attributes) {
//            return [
//                'requires' => 0.5,
//            ];
//        });
//    }


    /**
     * Returns a motion which requires 2/3
     */
    public function twoThirds()
    {
        return $this->state(function (array $attributes) {
            return [
                'requires' => 0.66,
            ];
        });
    }

    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_complete' => true,
            ];
        });
    }


    public function current()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_current' => true,
            ];
        });
    }

    public function procedural()
    {
        return $this->state(function (array $attributes) {

            $temps = collect(MotionTemplateRepository::$templates);
            $temps = $temps->whereIn('type', Motion::$proceduralTypes);
            $temp = $temps->random();
            //name is just used by the client to label the button
            unset($temp['name']);
            return $temp;
        });
    }


    /**
     * A motion used in an election
     */
    public function electedOfficeSingleChoice()
    {

        return $this->state(function (array $attributes) {
            return [
                /** The office being voted upon */
                'content' => "Election for {$this->faker->jobTitle}",

                'description' => "Please vote for one of the following candidates",

                'requires' => 1.0,
                'type' => 'election',
                'max_winners' => 1,
                'seconded' => true,
            ];
        });
    }


    /**
     * A motion used in an election which does not specify number of winners
     */
    public function electedOffice()
    {

        return $this->state(function (array $attributes) {
            return [
                /** The office being voted upon */
                'content' => "Election for {$this->faker->jobTitle}",

                'description' => "Please vote for one of the following candidates",

                'requires' => 1.0,
                'type' => 'election',
//                'max_winners' => 1,
                'seconded' => true,
            ];
        });
    }
}
