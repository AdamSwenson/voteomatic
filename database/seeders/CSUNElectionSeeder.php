<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\Motion;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CSUNElectionSeeder extends Seeder
{
public static $electionProps =  [
    'name' => "CSUN Faculty Election"
] ;
    public static $singleChoiceOffices = [
        [
            'content' => 'President of the Faculty',
            'description' => "Tremble before the might of this office"
        ],
        [
            'content' => 'Vice-President of the Faculty',
            'description' => "In charge of all vice"
        ],
        [
            'content' => 'Secretary of the Faculty',
            'description' => "Minutes matters"
        ],

        [
            'content' => 'Statewide Academic Senate Representative',
            'description' => "Representatives of the Faculty to the Academic Senate of the California State University
            shall be full-time members of the Faculty elected by the Faculty in such numbers, for such terms, and at such times as may be provided in the Constitution of that body."
        ],

    ];

    public static $multiwinnerOffices = [
        [
            'content' => 'Senator-at-Large',
            'description' => "Twelve Senators shall be elected at-large by and from the Faculty. (III.1.2.1)",
            'max_winners' => 8
        ],
    ];

    public static $propositions = [
        [
            'info->name' => "Addition of a dedicated lecturer seat to senate exec",
            'description' => "[text of addition goes here]",
            'requires' => 0.66,
            'type' => 'proposition',
            'is_resolution' => true,
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->faker = Factory::create();

        $election = Meeting::factory()->election()->create(self::$electionProps);

        foreach(self::$singleChoiceOffices as $office){
            $office['meeting_id'] = $election->id;
            Motion::factory()
                ->electedOfficeSingleChoice()
                ->create($office);
        }


        foreach(self::$multiwinnerOffices as $office){
            $office['meeting_id'] = $election->id;
            Motion::factory()
                ->electedOffice()
                ->create($office);
        }

        foreach (self::$propositions as $p){
            $p['meeting_id'] = $election->id;
            Motion::factory()->proposition()->create($p);
        }

        $this->command->line("\n Fake CSUN election (no candidates): {$election->id}");
    }
}
