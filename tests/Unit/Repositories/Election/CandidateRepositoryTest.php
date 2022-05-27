<?php

namespace Tests\Repositories\Election;

use App\Exceptions\BallotStuffingAttempt;
use App\Exceptions\WriteInDuplicatesOfficial;
use App\Models\Election\Candidate;
use App\Models\Election\Person;
use App\Models\Meeting;
use App\Models\Motion;
use App\Repositories\Election\CandidateRepository;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class CandidateRepositoryTest extends TestCase
{

    /**
     * @var \Illuminate\Support\HigherOrderCollectionProxy|mixed
     */
    public $person;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public $candidate;

    public function setUp(): void
    {
        parent::setUp();
        $fields = ['department', 'tacos'];
        $this->meeting = Meeting::factory()->election()->create(['info' => ['candidateFields' => $fields]]);
        $this->motion = Motion::factory()->electedOffice()->create(['meeting_id' => $this->meeting->id]);
        $this->person = Person::factory()->create([
            'info' =>
                [
                    'department' => 'CS',
                    'tacos' => 'pastor'
                ]
        ]);
        $this->candidate = Candidate::factory()->create(['person_id' => $this->person->id, 'motion_id' => $this->motion->id]);

        $this->object = new CandidateRepository();
    }

    /** @test */
    public function checkForDuplicationHappyPath()
    {
        $newPerson = Person::factory()->create();

        $result = $this->object->checkForDuplication($newPerson->first_name, $newPerson->last_name, $newPerson->info, $this->motion);

        $this->assertEmpty($result);
    }


    /** @test */
    public function checkForDuplicationSameNameDifferentInfo()
    {
        $newPerson = Person::factory()->create([
            'first_name' => $this->person->first_name,
            'last_name' => $this->person->last_name,
            'info' => ['department' => 'tacos']
        ]);

        $result = $this->object->checkForDuplication($newPerson->first_name, $newPerson->last_name, $newPerson->info, $this->motion);

        $this->assertEmpty($result);
    }

    /** @test */
    public function checkForDuplicationSameNameNoInfoProvided()
    {
        $newPerson = Person::factory()->create([
            'first_name' => $this->person->first_name,
            'last_name' => $this->person->last_name,
            'info' => []
        ]);
$this->expectException(WriteInDuplicatesOfficial::class);
        $result = $this->object->checkForDuplication($newPerson->first_name, $newPerson->last_name, $newPerson->info, $this->motion);

    }

    /** @test */
    public function checkForDuplicationDifferentNameNoInfoProvided()
    {
        $newPerson = Person::factory()->create([
            'first_name' => $this->person->first_name . "abcf",
            'last_name' => $this->person->last_name  . "abcf",
            'info' => []
        ]);
//        $this->expectException(WriteInDuplicatesOfficial::class);
        $result = $this->object->checkForDuplication($newPerson->first_name, $newPerson->last_name, $newPerson->info, $this->motion);

        $this->assertEmpty($result);
    }

    /** @test */
    public function checkForDuplicationWhenMeetingDoesNotDefineFields()
    {
        //dev This was the issue in VOT-181. I.e., using $candidateFields = $motion->meeting->info['candidateFields'] caused error
        $this->meeting->info =[];
        $this->meeting->save();

        $newPerson = Person::factory()->create([
            'first_name' => $this->person->first_name . "abcf",
            'last_name' => $this->person->last_name  . "abcf",
            'info' => []
        ]);
//        $this->expectException(WriteInDuplicatesOfficial::class);
        $result = $this->object->checkForDuplication($newPerson->first_name, $newPerson->last_name, $newPerson->info, $this->motion);

        $this->assertEmpty($result);
    }


    /** @test */
    public function checkForDuplicationExactDuplicate()
    {
        $this->expectException(WriteInDuplicatesOfficial::class);

        $newPerson = Person::factory()->create([
            'first_name' => $this->person->first_name,
            'last_name' => $this->person->last_name,
            'info' => $this->person->info
        ]);

        $result = $this->object->checkForDuplication($newPerson->first_name, $newPerson->last_name, $newPerson->info, $this->motion);


    }



    /** @test */
    public function doesInfoMatchWhenTrue()
    {
        $person1 = Person::factory()->create([
            'info' =>
                [
                    'department' => 'CS',
                    'tacos' => 'pastor'
                ]
        ]);

        $person2 = Person::factory()->create([
            'info' =>
                [
                    'department' => 'CS',
                    'tacos' => 'pastor'
                ]
        ]);
        $result = $this->object->doesInfoMatch($person1, $person2);
        $this->assertTrue($result);
    }

    /** @test */
    public function doesInfoMatchWhenFalse()
    {
        $person1 = Person::factory()->create([
            'info' =>
                [
                    'department' => 'CS',
                    'tacos' => 'pastor'
                ]
        ]);

        $person2 = Person::factory()->create([
            'info' =>
                [
                    'department' => 'CS',
                    'tacos' => 'asada'
                ]
        ]);
        $result = $this->object->doesInfoMatch($person1, $person2);
        $this->assertFalse($result);
    }

    /** @test */
    public function testaddPersonToPool()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function addWriteInCandidate()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function removeCandidateFromPool()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function addCandidateToBallot()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function getOfficialCandidatesForOffice()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function removeCandidateFromBallot()
    {
        $this->markTestIncomplete();
    }

}
