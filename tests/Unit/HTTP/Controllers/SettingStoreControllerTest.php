<?php

namespace Tests\Http\Controllers;

use App\Http\Controllers\SettingStoreController;

//use PHPUnit\Framework\TestCase;
use App\Models\Meeting;
use App\Models\SettingStore;
use App\Models\User;
use Illuminate\Support\Arr;
use Tests\TestCase;

class SettingStoreControllerTest extends TestCase
{

    /**
     * @var Meeting
     */
    public $meeting;
    /**
     * @var SettingStore
     */
    public $settings;
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public $user;
    /**
     * @var string
     */
    public $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->object = new SettingStoreController();
        $this->user = User::factory()->create();
        $this->meeting = Meeting::factory()->create();
        $this->meeting->addUserToMeeting($this->user);

        $this->url = '/settingStores/';

    }


    public function testModel()
    {
        //just making sure I set up everything correctly
        $this->settings = new SettingStore();
        $this->settings->meeting()->associate($this->meeting);
        $this->settings->user()->associate($this->user);

        $this->assertEquals($this->user->id, $this->settings->user_id);

        $this->assertEquals($this->meeting->id, $this->settings->meeting_id);
    }
//
//    /** @test */
//    public function testindex()
//    {
//
//    }

    /** @test */
    public function store()
    {

        $data = [
            'meetingId' => $this->meeting->id,
            'settings' => [
                'show_vote_counts' => true
            ]
        ];

        $response = $this->actingAs($this->user)->post($this->url, $data);

        $response->assertSuccessful();
        $response->assertJsonFragment(['settings' => $data['settings']]);
    }

    /** @test */
    public function storeRejectsNonMembers()
    {
        $nonMember = User::factory()->create();
        $data = [
            'meetingId' => $this->meeting->id,
            'settings' => [
                'show_vote_counts' => true
            ]
        ];

        $response = $this->actingAs($nonMember)->post($this->url, $data);


        //check
        $response->assertStatus(403);

    }

    /** @test */
    public function storeRejectsMemberAttemptToSetChairOnly()
    {
        $nonChair = User::factory()->create();
        $this->meeting->addUserToMeeting($nonChair);

        $restrictedSetting = $this->faker->randomElement(SettingStore::CHAIR_ONLY_SETTINGS);
        $data = [
            'meetingId' => $this->meeting->id,
            'settings' => [
                $restrictedSetting => true
            ]
        ];

        $response = $this->actingAs($nonChair)->post($this->url, $data);


        //check
        $response->assertStatus(403);
    }


    public function storeAllowsChairToSetChairOnly()
    {
        $restrictedSetting = $this->faker->randomElement(SettingStore::CHAIR_ONLY_SETTINGS);
        $data = [
            'meetingId' => $this->meeting->id,
            'settings' => [
                $restrictedSetting => true
            ]
        ];

        $response = $this->actingAs($this->user)->post($this->url, $data);


        //check
        $response->assertSuccessful();
    }


    /** @test */
    public function destroy()
    {

        $setting = SettingStore::factory()->create([
            'user_id' => $this->user->id,
            'meeting_id' => $this->meeting->id
        ]);
//        $this->assertEquals($this->meeting->id, $setting->meeting_id);
//        $setting->save();
//
//        $setting = SettingStore::where('meeting_id', $this->meeting->id)->where('user_id', $this->user->id)->first();
//
//        $this->assertNotEmpty($setting);
//        $this->assertNotEmpty($setting->meeting);
        //$this->markTestSkipped();
//        $restrictedSetting = $this->faker->randomElement(Settings::CHAIR_ONLY_SETTINGS);
//
//        $this->assertTrue(in_array($restrictedSetting, Settings::CHAIR_ONLY_SETTINGS, ));
    }

    /** @test */
    public function update()
    {
        $setting = SettingStore::factory()->create([
            'user_id' => $this->user->id,
            'meeting_id' => $this->meeting->id
        ]);
        $setting->save();


        $data = [
            'meetingId' => $this->meeting->id,
            'settings' => [
                'show_vote_counts' => !$setting->getSetting('show_vote_counts')
            ]
        ];
        $url = $this->url . $setting->id;

        $response = $this->actingAs($this->user)->put($url, $data);

        $response->assertSuccessful();
        $response->assertJsonFragment(['settings' => $data['settings']]);
    }



    /** @test */
    public function updatePreventsNonChairFromChairOnlySettings()
    {
        $setting = SettingStore::factory()->create([
            'user_id' => $this->user->id,
            'meeting_id' => $this->meeting->id
        ]);
        $setting->save();

        $nonChair = User::factory()->create();
        $this->meeting->addUserToMeeting($nonChair);

        $restrictedSetting = $this->faker->randomElement(SettingStore::CHAIR_ONLY_SETTINGS);
        $data = [
            'meetingId' => $this->meeting->id,
            'settings' => [
                $restrictedSetting => true
            ]
        ];
        $url = $this->url . $setting->id;

        $response = $this->actingAs($nonChair)->put($url, $data);

        //check
        $response->assertStatus(403);
    }


    /** @test */
    public function updatePreventsMemberFromChangingDifferentMemberSettings()
    {

    }


    /** @test */
    public function testshow()
    {

    }

}
