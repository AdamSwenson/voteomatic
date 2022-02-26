<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\Motion;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CSUNStandingCommitteeSeeder extends Seeder
{

    public static $electionProps =  [
        'name' => "CSUN Standing Committee Election"
    ] ;

    public static $multiwinnerOffices = [
//        [
//            'content' => 'Academic Technology Committee',
//            'description' => "This committee shall consist of thirteen members: nine members elected, one each, by and from the eight Colleges and the Library; two members elected by the Senate; and two student members appointed by the Associated Students Senate.This committee shall make recommendations regarding University resources and policy that pertain to instructional and research technology, including computing and instructional media development and services. There shall be consultation and liaison with other appropriate Standing Committees. (V.6.1)",
//            'max_winners' => 8
//        ],
        [
            'content' => 'Educational Equity Committee',
            'description' =>
                "This committee shall consist of twelve members: eight members elected by the Senate; two members appointed by the President of the University; and two student members, one graduate and one undergraduate, appointed by the Associated Students Senate.
This committee shall study educational equity policy and make recommendations affecting educational equity;
review all existing University, College and departmental educational equity efforts; review all educational equity expenditures and allocations as necessary; and review proposals for new educational equity programs. The Director of the Educational Opportunities Program (EOP) shall serve as the executive secretary of the committee. (V.6.2)",
            'max_winners' => 8
        ],

        [
            'content' => "Educational Policies Committee" ,
            'description' => "This committee shall consist of eleven members: eight members elected by the Senate, two members appointed by the President of the University, and one student member appointed by the Associated Students Senate.
This committee shall study policy areas and make recommendations affecting undergraduate curriculum, general education, and undergraduate academic standards. (V.6.3)",
            'max_winners' => 8
        ],

        [
            'content' => 'Educational Resources Committee',
            'description' => "This committee shall consist of ten tenured members of the Faculty: eight members elected by the Senate and two members appointed by the President of the University.
This committee shall make general policy recommendations in order to guide the allocation of all University resources that impact educational programs. In carrying out its charge, the committee may review and advise on current and proposed allocation of faculty positions; the allocation of and projected needs for space,
support equipment and operating expense budgets; the allocation of resources for technology; the assignment of and projected needs for support staff; the recommendations of other faculty governance committees which have significant educational resource implications; and, at the request of an appropriate University committee or an Associate Dean, independently evaluate proposals for new programs with regard to their impact on the available educational resources of the University. (V.6.4)
",
            'max_winners' => 8
        ],
        [
            'content' => "Extended Learning Committee",
            'description' => "
This committee shall consist of twelve members: nine members elected, one each, by and from the eight Colleges and the Library; two members elected by the Senate; and one member appointed by the President of the University.
This committee shall study policy areas and make recommendations affecting continuing education. (V.6.5)",
            'max_winners' => 2
        ],
        [
            'content' => "Graduate Studies Committee",
            'description' => "This committee shall consist of thirteen members: ten members elected by the Senate, two members appointed by the President of the University, and one graduate student member appointed by the Associated Students Senate.
This committee shall study policy areas and make recommendations affecting graduate curricula and graduate academic standards. It shall maintain liaison with the Educational Policies Committee on curricular matters of mutual interest. (V.6.6)",
            'max_winners' => 10
        ],
        [
            'content' => "Library Committee",
            'description' => "This committee shall consist of thirteen members: nine members elected, one each, by and from the eight Colleges and the Library; two members elected by the Senate; and two student members, one undergraduate and one graduate, appointed by the Associated Students Senate.
This committee shall make recommendations concerning the allocation of Library funds and the development of Library services and resources. There shall be consultation and liaison with other appropriate Standing Committees. (V.6.7)",
            'max_winners' => 2
        ],
        [
            'content' => "Research and Grants Committee",
            'description' => "This committee shall consist of eleven members: nine members elected, one each, by and from the eight Colleges and the Library; and two members elected by the Senate. A University administrator associated with research shall serve as executive secretary and as a resource person for the committee.
This committee shall make recommendations concerning faculty and University research, including general policy recommendations, and shall recommend procedures for maintaining adequate records and preparing reports of research activity undertaken at the University. (V.6.9)",
            'max_winners' => 2
        ],
        [
            'content' => "Learning Resource Advisory Committee",
            'description' => "This Advisory Committee shall consist of five members: four members elected by the Senate and one student member appointed by the Associated Students Senate. The Director of the Learning Resource Center shall serve as executive secretary.
This Advisory Committee shall make recommendations to the Director of the Learning Resource Center concerning the development and use of Learning Resource Center services and the allocation of Learning Resource Center funds. (V.7.1)",
            'max_winners' => 4
        ],
//        [
//            'content' => ,
//            'description' => ,
//            'max_winners' =>
//        ],
//        [
//            'content' => ,
//            'description' => ,
//            'max_winners' =>
//        ],
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

        foreach(self::$multiwinnerOffices as $office){
            $office['meeting_id'] = $election->id;
            Motion::factory()
                ->electedOfficeSingleChoice()
                ->create($office);
        }


        $this->command->line("\n Fake CSUN Standing Committee election (no candidates): {$election->id}");

    }
}
