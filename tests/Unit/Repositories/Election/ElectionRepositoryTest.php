<?php

namespace Tests\Repositories\Election;

use App\Jobs\DuplicateElection;
use App\Models\Election\Candidate;
use App\Models\Election\Person;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use App\Models\Vote;
use App\Repositories\Election\CandidateRepository;
use App\Repositories\Election\ElectionRepository;

//use PHPUnit\Framework\TestCase;
use Database\Seeders\CSUNElectionSeeder;
use Illuminate\Support\Collection;
use Tests\helpers\FakeFullElectionMaker;
use Tests\TestCase;

class ElectionRepositoryTest extends TestCase
{

    /**
     * @var int
     */
    public $loserTotal;
    /**
     * @var int
     */
    public $winnerTotal;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public $people;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public $motion;
    /**
     * @var mixed
     */
    public $winner;

    public $tiedCandidates = [];


    public function setUp(): void
    {
        parent::setUp();
        $this->object = new ElectionRepository();

        $this->user = User::factory()->create();

        $this->offices = Motion::factory()->electedOfficeSingleChoice()->count(5)->create();
        $this->propositions = Motion::factory()->proposition()->count(5)->create();

        $this->motion = Motion::factory()->electedOfficeSingleChoice()->create();

        $this->people = Person::factory()->count(10)->create();

        $this->loserTotal = 2;
        $this->winnerTotal = 5;
        $this->winner = $this->people[0];
        $this->voters = User::factory()->count(10)->create();

    }

    public function createFakeElection()
    {
        $candidateRepo = app()->make(CandidateRepository::class);

        $original = Meeting::factory()->election()->create();
        // add users
        $original->addUserToMeeting($this->user);
        $original->setOwner($this->user);
        $original->save();
        foreach ($this->voters as $voter) {
            $original->addUserToMeeting($voter);
        }

        //add offices
        foreach ($this->offices as $office) {
            $this->object->addOfficeToElection($original, $office->content, $office->description, $office->max_winners, $office->type);
            foreach ($this->people as $candidate) {
                $candidateRepo->addPersonToPool($office, $candidate);
                $candidateRepo->addCandidateToBallot($office, $candidate);
            }
        }

        //add propositions
        foreach ($this->propositions as $propositon) {
            $this->object->addOfficeToElection($original, $propositon->content, $propositon->description, $propositon->max_winners, $propositon->type);
        }

        return $original;
    }

    /** @test */
    public function duplicateElection()
    {
        $original = $this->createFakeElection();

        //call
        $result = $this->object->duplicateElection($original);

        //check
        //Basic properties of the election
        $this->assertTrue($result->is_election);
        $this->assertEquals($result->phase, 'setup');
        $this->assertEquals($original->name . " COPY", $result->name);
        $this->assertEquals($original->info, $result->info);

        //Check that users have been copied
        $this->assertTrue($result->isPartOfMeeting($this->user));
        $this->assertTrue($result->isOwner($this->user));
        foreach ($this->voters as $voter) {
            $this->assertTrue($result->isPartOfMeeting($voter));
        }

        //Check that motions were copied
        $this->assertEquals(sizeof($this->offices) + sizeof($this->propositions), sizeof($result->motions));
        $offices = collect($this->offices);
        $props = collect($this->propositions);
        $both = $offices->merge($props);

        foreach ($result->motions as $motion) {
            $this->assertNotContains($motion->id, $offices->pluck('id')->all(), "Motion ids in result are different from original office ids");
            $this->assertNotContains($motion->id, $props->pluck('id')->all(), "Motion ids in result are different from original proposition ids");
            $this->assertContains($motion->content, $both->pluck('content')->all(), "Motion content is in original offices or props");
        }

        //Check the pools and candidates
        foreach ($result->motions as $motion) {
            if ($motion->type == 'election') {
                $ogOffice = $offices->where('content', $motion->content)->first();
                $r = $motion->poolMembers;
                $this->assertEquals(sizeof($ogOffice->poolMembers), sizeof($motion->poolMembers), "Pool size the same");
//               foreach($ogOffice->poolMembers as $member) {
//
//               }


            }


        }
    }

    /** @test */
    public function duplicateMotionJob()
    {
        $meeting = $this->createFakeElection();
        $preCount = Meeting::all()->count();

        DuplicateElection::dispatch($meeting);
        $postCount = Meeting::all()->count();
        $this->assertEquals($preCount + 1, $postCount);
    }

//
//    /** @test */
//    public function addCandidate()
//    {
//        $this->makeSingleElectionResults();
//
//        $motion = Motion::factory()->electedOfficeSingleChoice()->create();
//        $name = $this->faker->name;
//        $info = $this->faker->sentence;
//        $result = $this->object->addCandidate($motion, $name, $info);
//
//        $this->assertInstanceOf(Candidate::class, $result);
//    }


//    /** @test */
//    public function getWinners()
//    {
//        $this->makeSingleElectionResults();
//
//        //call
//        $results = $this->object->getWinners($this->motion);
//
//        //check
//        $this->assertInstanceOf(Collection::class, $results, "Returns a collection ");
//
//        $results = $results->toArray();
//        $this->assertEquals(1, sizeof($results), "Only one result returned");
//
//        foreach ($results as $k => $v) {
//
//            $this->assertEquals($this->winner->name, $k, "Correct name as key");
//
//            $this->assertEquals($this->winnerTotal, $v, "Correct vote total as value");
//        }
////        $r = $results->first();
//
////        $this->assertInstanceOf(Candidate::class, $results->first(), "Correct object type returned");
////        $this->assertTrue($this->winner->is($results->()), "Correct candidate returned");
////        $this->assertEquals($this->winner->name, $results[0]->name, "Correct");
//
//    }
//
//
//    /** @test */
//    public function getWinnersReturningCandidateObjects()
//    {
//        $this->makeSingleElectionResults();
//
//        //call
//        $results = $this->object->getWinners($this->motion, true);
//
//        //check
//        $this->assertInstanceOf(Collection::class, $results, "Returns a collection ");
//        $this->assertEquals(1, sizeof($results), "Only one result returned");
//
//        $result = $results->first();
//        $this->assertInstanceOf(Candidate::class, $result, "Correct object type returned");
//        $this->assertTrue($this->winner->is($result), "Correct candidate returned");
//
//    }
//
//
//    /** @test */
//    public function getWinnersReturningCandidateObjectsWithTies()
//    {
//        $numTied = $this->faker->numberBetween(2, sizeof($this->candidates));
//        $this->makeTiedElectionResults($numTied);
//
//        //call
//        $results = $this->object->getWinners($this->motion, true);
//
//        //check
//        $this->assertInstanceOf(Collection::class, $results, "Returns a collection ");
//        $this->assertEquals($numTied, $results->count(), "Expected number of results returned");
//
//        foreach ($results as $result){
//            $this->assertInstanceOf(Candidate::class, $result, "Correct object type returned");
//            $this->assertContains($result, $this->tiedCandidates, "Correct candidate returned");
//        }
//
//    }


//    /** @test */
//    public function getResults()
//    {
//        $this->makeSingleElectionResults();
//
//        $results = $this->object->getResults($this->motion);
//
//        $this->assertInstanceOf(Collection::class, $results, "Returns a collection ");
//        $scores = $results;
//
//        $sorted = $results->sortDesc();
//
//        $this->assertEquals($sorted, $scores, "Returned scores in descending order");
//
//        foreach ($results->toArray() as $k => $v) {
//            $this->assertIsString($k, "The returned collection (probably) has candidate names as keys");
//            $this->assertIsInt($v, "The returned collection (probably) has vote total as value");
//        }
//    }
//
//
//    /** @test */
//    public function getResultsReturningCandidateObjects()
//    {
//        $this->makeSingleElectionResults();
//
//        $results = $this->object->getResults($this->motion, true);
//
//        //check
//        $this->assertInstanceOf(Collection::class, $results, "Returns a collection ");
//        $scores = $results;
//
//        $sorted = $results->sortDesc();
//        $this->assertEquals($sorted, $scores, "Returned scores in descending order");
//
//        foreach ($results as $result) {
//            $this->assertInstanceOf(Candidate::class, $result, "Returned collection contains Candidate objects");
//        }
//    }
//
//
//    /** @test */
//    public function getResultsTies()
//    {
//        $numTied = $this->faker->numberBetween(2, sizeof($this->candidates));
//        $this->makeTiedElectionResults($numTied);
//
//        $results = $this->object->getResults($this->motion);
//
//        $this->assertInstanceOf(Collection::class, $results, "Returns a collection ");
//        $scores = $results;
//
//        $sorted = $results->sortDesc();
//
//        $this->assertEquals($sorted, $scores, "Returned scores in descending order");
//
//        foreach ($results->toArray() as $k => $v) {
//            $this->assertIsString($k, "The returned collection (probably) has candidate names as keys");
//            $this->assertIsInt($v, "The returned collection (probably) has vote total as value");
//        }
//    }
//
//
//    /** @test */
//    public function getVotePercentages()
//    {
//        $this->makeSingleElectionResults();
//
//        $results = $this->object->getVotePercentages($this->motion);
//
//        //check
//        $this->assertInstanceOf(Collection::class, $results, "Returns a collection ");
//
//        $scores = $results->pluck('pct_of_total');
//        $sorted = $scores->sortDesc();
//        $this->assertEquals($sorted, $scores, "Returned vote percentages in descending order");
//
//        foreach ($results as $result) {
//            $this->assertInstanceOf(Candidate::class, $result['candidate'], "Returned collection contains Candidate objects");
//        }
//
//    }

    /** @test */
    public function addOfficeToElection()
    {
        $election = Meeting::factory()->election()->create();

        $name = $this->faker->company;
        $description = $this->faker->sentence;

        //call
        $result = $this->object->addOfficeToElection($election, $name, $description);

        //check
        $this->assertInstanceOf(Motion::class, $result);
        $this->assertEquals($name, $result->content);
        $this->assertEquals($description, $result->description);

        $this->assertTrue($result->is($election->motions()->where('id', $result->id)->first()), "office associated with election");

    }




    /* **************************** Utilities  **************************** */


    /**
     * @param int $numberTied
     */
    public function makeTiedElectionResults($numberTied = 2): void
    {

        for ($i = 0; $i < sizeof($this->people); $i++) {

            if ($i < $numberTied) {
                //We have a winner!
                Vote::factory()
                    ->count($this->winnerTotal)
                    ->create(
                        ['motion_id' => $this->motion->id,
                            'candidate_id' => $this->people[$i]->id
                        ]);

                $this->tiedCandidates[] = $this->people[$i];
            }


            //Thanks for playing!
            $loserTotal = $this->faker->numberBetween(0, $this->winnerTotal - 1);
            Vote::factory()
                ->count($loserTotal)
                ->create(
                    ['motion_id' => $this->motion->id,
                        'candidate_id' => $this->people[$i]->id
                    ]);

        }
    }


    public function makeSingleElectionResults(): void
    {
//winner
        Vote::factory()->count($this->winnerTotal)
            ->create(
                ['motion_id' => $this->motion->id,
                    'candidate_id' => $this->people[0]->id
                ]);


        for ($i = 1; $i < sizeof($this->people); $i++) {
            $loserTotal = $this->faker->numberBetween(0, $this->winnerTotal - 1);
            Vote::factory()
                ->count($loserTotal)
                ->create(
                    ['motion_id' => $this->motion->id,
                        'candidate_id' => $this->people[$i]->id
                    ]);

//            for ($c = 0; $c < $this->loserTotal; $c++) {
//                Vote::factory()->create(
//                    ['motion_id' => $this->motion->id,
//                        'candidate_id' => $this->candidates[$i]->id
//                    ]);
//            }
        }
    }

}
