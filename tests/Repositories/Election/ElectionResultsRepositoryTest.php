<?php

namespace Tests\Repositories\Election;

use App\Models\Election\Candidate;
use App\Models\Vote;
use App\Repositories\Election\ElectionResultsRepository;

//use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ElectionResultsRepositoryTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

    }

    /** @test */
    public function testgetResultsForClient()
    {

    }

    /* **************************** Utilities  **************************** */


    /**
     * @param int $numberTied
     */
    public function makeTiedElectionResults($numberTied = 2): void
    {

        for ($i = 0; $i < sizeof($this->candidates); $i++) {

            if ($i < $numberTied) {
                //We have a winner!
                Vote::factory()
                    ->count($this->winnerTotal)
                    ->create(
                        ['motion_id' => $this->motion->id,
                            'candidate_id' => $this->candidates[$i]->id
                        ]);

                $this->tiedCandidates[] = $this->candidates[$i];
            }


            //Thanks for playing!
            $loserTotal = $this->faker->numberBetween(0, $this->winnerTotal - 1);
            Vote::factory()
                ->count($loserTotal)
                ->create(
                    ['motion_id' => $this->motion->id,
                        'candidate_id' => $this->candidates[$i]->id
                    ]);

        }
    }


    public function makeSingleElectionResults(): void
    {
//winner
        Vote::factory()->count($this->winnerTotal)
            ->create(
                ['motion_id' => $this->motion->id,
                    'candidate_id' => $this->candidates[0]->id
                ]);


        for ($i = 1; $i < sizeof($this->candidates); $i++) {
            $loserTotal = $this->faker->numberBetween(0, $this->winnerTotal - 1);
            Vote::factory()
                ->count($loserTotal)
                ->create(
                    ['motion_id' => $this->motion->id,
                        'candidate_id' => $this->candidates[$i]->id
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
