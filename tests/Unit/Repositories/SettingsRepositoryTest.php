<?php

namespace Tests\Unit\Repositories;

use App\Models\Meeting;
use App\Models\SettingStore;
use App\Models\User;
use App\Repositories\SettingsRepository;
use Tests\TestCase;

//use PHPUnit\Framework\TestCase;

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
    public function createSettingStore()
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
        $this->assertEquals($this->meeting->id,  $result->meeting->id, "meeting properly associated with settings");
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

    /** @test */
    public function getConsolidatedSettingsWhereUserSettingsExist(){

        $mm = SettingStore::factory()->meetingMaster()->create([
            'meeting_id' => $this->meeting->id,
            'settings' => [
                'show_vote_counts' => true
            ]
        ]);

        $us = SettingStore::factory()->create([
            'meeting_id' => $this->meeting->id,
            'user_id' => $this->user->id,
            'settings' => [
                'show_vote_counts' => false,
                'dog' => 'wag'
            ]
        ]);

        $result = $this->object->getConsolidatedSettings($this->meeting, $this->user);

        $this->assertInstanceOf(SettingStore::class, $result);

//        $this->assertEquals($this->user->id, $result->user_id);
        $this->assertTrue(is_null($result->is_meeting_master) || $result->is_meeting_master === false, "meeting master props outside of settings not merged in");
        foreach($mm->settings as $name => $v){
            $this->assertEquals($v, $result->settings[$name], 'meeting master settings merged in');
        }

        foreach($us->settings as $name => $v){
            if(! in_array($name, $mm->getSettingNames())){
                $this->assertEquals($v, $result->settings[$name], 'non conflicting user settings untouched');
            }
        }

    }


    /** @test */
    public function getConsolidatedSettingsWhereUserSettingsDoNotExist(){
        $mm = SettingStore::factory()->meetingMaster()->create([
            'meeting_id' => $this->meeting->id,
            'settings' => [
                'show_vote_counts' => false
            ]
        ]);

        $result = $this->object->getConsolidatedSettings($this->meeting, $this->user);

        $this->assertInstanceOf(SettingStore::class, $result);

//        $this->assertEquals($this->user->id, $result->user_id);
        $this->assertEmpty($result->is_meeting_master, "meeting master props outside of settings not merged in");

        $this->assertEquals($mm->settings, $result->settings, 'meeting master settings merged');
    }


    /** @test */
    public function getConsolidatedSettingsWhereMeetingMasterSettingsAreNull(){
        $mm = SettingStore::factory()->meetingMaster()->create([
            'meeting_id' => $this->meeting->id,
        ]);

        $result = $this->object->getConsolidatedSettings($this->meeting, $this->user);

        $this->assertInstanceOf(SettingStore::class, $result);

//        $this->assertEquals($this->user->id, $result->user_id);
        $this->assertEmpty($result->is_meeting_master, "meeting master props outside of settings not merged in");

        $this->assertEquals($mm->settings, $result->settings, 'meeting master settings merged');
    }



}
