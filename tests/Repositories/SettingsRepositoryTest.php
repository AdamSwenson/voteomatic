<?php

namespace Tests\Repositories;

use App\Models\Meeting;
use App\Models\SettingStore;
use App\Models\User;
use App\Repositories\SettingsRepository;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class SettingsRepositoryTest extends TestCase
{

    public $meeting;

    public function setUp(): void
    {
        parent::setUp();

        $this->object = new SettingsRepository();
        $this->meeting = Meeting::factory()->create();
        $this->user = User::factory()->create();
        $this->meeting->addUserToMeeting($this->user);

    }

    /** @test */
    public function testcreateSettingStore()
    {
        $this->markTestSkipped();
    }

    /** @test */
    public function createMeetingMasterWhereNoPreexisting()
    {

        $result = $this->object->createMeetingMaster($this->meeting);

        //check
        $this->assertInstanceOf(SettingStore::class, $result);
        $this->assertTrue($result->is_meeting_master);
    }


    /** @test */
    public function createMeetingMasterWherePreexisting()
    {
//        $settings = SettingStore::factory()->create(['meeting_id' => $this->meeting->id,
//            'is_meeting_master' => true
//        ]);

        $settings = SettingStore::factory()->meetingMaster()->create([
            'meeting_id' => $this->meeting->id,
        ]);

        $result = $this->object->createMeetingMaster($this->meeting);

        //check
        $this->assertInstanceOf(SettingStore::class, $result);
        $this->assertTrue($result->is_meeting_master);
        $this->assertEquals($settings->id, $result->id, "Returned the preexisting one");
    }

    /** @test */
    public function getMeetingMaster()
    {
//ok so this isn't really a test of the repo. sue me.
        $settings = SettingStore::factory()->meetingMaster()->create([
            'meeting_id' => $this->meeting->id,
        ]);
        $settings->save();
        $settings->push();


        $this->meeting->refresh();

        $result = $this->meeting->getMasterSettingStore();
        $this->assertInstanceOf(SettingStore::class, $result);
        $this->assertTrue($result->is_meeting_master);
        $this->assertEquals($settings->id, $result->id, "Returned the preexisting one");

    }

    /** @test */
    public function syncSettingsToMeetingMaster()
    {
        $mm = SettingStore::factory()->meetingMaster()->create([
            'meeting_id' => $this->meeting->id,
        ]);

        $settings = SettingStore::factory()->count(20)->create([
            'meeting_id' => $this->meeting->id,
        ]);

        $this->object->syncSettingsToMeetingMaster($this->meeting);

        $ss = SettingStore::where('meeting_id', $this->meeting->id)->get();

        foreach ($ss as $s) {
            $this->assertInstanceOf(SettingStore::class, $s);
            foreach ($s->settings as $k => $v) {
                $this->assertEquals($mm->settings[$k], $v);
            }
        }


    }

    /** @test */
    public function updateMeetingMaster()
    {
        $mm = SettingStore::factory()->meetingMaster()->create([
            'meeting_id' => $this->meeting->id,
            'settings' => [
                'show_vote_counts' => false
            ]
        ]);

        $this->object->updateMeetingMaster($this->meeting, 'show_vote_counts', true);

        $this->meeting->refresh();

        $mm2 = $this->meeting->getMasterSettingStore();
        $this->assertTrue($mm2->getSetting('show_vote_counts'));

    }

}
