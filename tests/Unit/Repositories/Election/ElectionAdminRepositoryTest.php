<?php

namespace Tests\Unit\Repositories\Election;

use App\Models\Election\Candidate;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\RecordedVoteRecord;
use App\Models\User;
use App\Repositories\Election\ElectionAdminRepository;
use App\Repositories\Election\ElectionVoteRepository;
use App\Repositories\SettingsRepository;
use Tests\TestCase;

//use PHPUnit\Framework\TestCase;


class ElectionAdminRepositoryTest extends TestCase
{
    public $numMotions;
    public SettingsRepository $settingsRepo;

    public function setUp(): void
    {
        $this->settingsRepo = new SettingsRepository();
        parent::setUp();
        $this->numMotions = 5;
        $this->election = Meeting::factory()->election()->create();
        $this->settingsRepo->createMeetingMaster($this->election);
        $this->motions = Motion::factory()->electedOfficeSingleChoice()->count($this->numMotions)->create();
        foreach ($this->motions as $motion) {
            $this->election->motions()->save($motion);
        }
        $this->object = new ElectionAdminRepository();
    }

    /** @test */
    public function purgeAndPermanentlyLockElection(){
        $this->election->getMasterSettingStore()->setSetting('permalock_election', true);

        $numVotes = 5;
        foreach ($this->motions as $motion) {
            for($i = 0; $i < $numVotes; $i++){
                $u = User::factory()->create();
                $n = RecordedVoteRecord::factory()->create(['motion_id' => $motion->id, 'user_id' => $u->id]);
            }
        }
        $this->assertNull($this->election->is_permalocked);
//        $recs = RecordedVoteRecord::all();
        $this->assertEquals($numVotes * $this->numMotions, sizeof(RecordedVoteRecord::all()));

//        $this->assertEquals($numVotes, sizeof($this->election->motions->recordedVoteRecords));

        //call
        $result = $this->object->purgeAndPermanentlyLockElection($this->election);

        $this->assertTrue($this->election->is_permalocked);

    }


    /** @test */
    public function purgeAndPermanentlyLockElectionDoesNotLockIfSettingsProhibit(){
        $numVotes = 5;
        foreach ($this->motions as $motion) {
            for($i = 0; $i < $numVotes; $i++){
                $u = User::factory()->create();
                $n = RecordedVoteRecord::factory()->create(['motion_id' => $motion->id, 'user_id' => $u->id]);
            }
        }
        $this->assertNull($this->election->is_permalocked);
//        $recs = RecordedVoteRecord::all();
        $this->assertEquals($numVotes * $this->numMotions, sizeof(RecordedVoteRecord::all()));

//        $this->assertEquals($numVotes, sizeof($this->election->motions->recordedVoteRecords));

        //call
        $result = $this->object->purgeAndPermanentlyLockElection($this->election);

        $this->assertFalse($result);
        $this->assertNull($this->election->is_permalocked);
    }

}
