<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Repositories\ISettingsRepository;
use Illuminate\Database\Seeder;

class MasterSettingsSeeder extends Seeder
{
    /**
     * Adds a settingStore object for every meeting
     *
     *
     * @return void
     */
    public function run()
    {
        $repo = app()->make(ISettingsRepository::class);
       foreach(Meeting::all() as $meeting){
           $repo->createMeetingMaster($meeting);
           //SettingStore::factory()->meetingMaster()->create(['meeting_id' => $meeting->id]);
       }
    }
}
