<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\Motion;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CSUNElectionSeeder extends Seeder
{

    const PROP_TEXT = <<<DOC

<div class="propText">
<!--<blockquote class="blockquote">-->
<p class="card-text">That Article IV Section 1 of the Bylaws of the Faculty be revised to read (additions indicated by underlining):</p>

<p class="ms-3 me-3">There shall be constituted each year a twelve-member Executive Committee consisting of: the
President of the Faculty, the Vice President of the Faculty, the Secretary of the
Faculty, the senior representative of the CSU Academic Senate, the Provost and
Vice President for Academic Affairs (non-voting), <u>one Lecturer elected by and
from the Senate,</u> and six members of the faculty elected by and from the Senate.</p>
<p  class="ms-3 me-3">The Executive Committee shall have no more than two members from any one
College except in the event that more than two ex officio members are from one
College <u>and the two-college rule shall not apply to the elected Lecturer
representative to the Senate Executive Committee</u>.</p>
<p  class="ms-3 me-3">The six members elected by the Senate shall be from Colleges that do not already
have two representatives on the committee. The President of the Faculty shall
serve as chair of the Executive Committee.</p>
<!--</blockquote>-->
</div>
DOC;

    const PROP_SUPP = <<<DOC
<p>For information about the role of the Senate Executive Committee, please see
<a href="https://www.csun.edu/faculty-senate/about-senate-executive-committee" target="_blank" rel="noopener noreferrer"
>https://www.csun.edu/faculty-senate/about-senate-executive-committee</a>
</p>
<p>For information about the process for changing the Bylaws, please see Article VII of the
<a href="http://www.csun.edu/sites/default/files/Bylaws.pdf">Bylaws of the Faculty</a></p>

DOC;


public static $electionProps =  [
    'name' => "CSUN Faculty Election",
    'info->candidateFields' =>['link', 'department']
] ;
    public static $singleChoiceOffices = [
        [
            'content' => 'President of the Faculty',
            'description' => "" //"Tremble before the might of this office"
        ],
        [
            'content' => 'Vice-President of the Faculty',
            'description' => "" //"In charge of all vice"
        ],
        [
            'content' => 'Secretary of the Faculty',
            'description' => "" //"Minutes matters"
        ],

        [
            'content' => 'Statewide Academic Senate Representative',
            'description' => "Representatives of the Faculty to the Academic Senate of the California State University
            shall be full-time members of the Faculty elected by the Faculty in such numbers, for such terms, and at such times as may be provided in the Constitution of that body. (II.7)"
        ],

    ];

    public static $multiwinnerOffices = [
        [
            'content' => 'Senator-at-Large',
            'description' => "Twelve Senators shall be elected at-large by and from the Faculty. (III.1.2.1)",
            'max_winners' => 12
        ],
    ];

    public static $propositions = [
        [
            'info->name' => "Addition of a dedicated lecturer seat to the Executive Committee of the Senate",
            'content' => self::PROP_TEXT,

            'description' => self::PROP_SUPP,
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
