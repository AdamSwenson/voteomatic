<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\Motion;
use App\Models\Vote;
use App\Repositories\IMotionRepository;
use Illuminate\Database\Seeder;

class FakeFullMeetingSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $voters = User::factory()->count(FakeFullMeetingSeeder::NUMBER_VOTERS)->create();

        $repo  = app()->make(IMotionRepository::class);
        $meeting = Meeting::factory()->create();


        $main1 = Motion::create([
           'content' => "That the dog not be given hamburgers",
           'requires' => 0.5,
           'meeting_id' => $meeting->id,
           'type' => 'main'
        ]);


        //insert-primary
        //attempt to water it down
        $m1a1 = Motion::create([
            'content' => "That the dog not be given 100 hamburgers",
            'requires' => 0.5,
            'meeting_id' => $meeting->id,
            'type' => 'amendment',
            'applies_to' => $main1->id
        ]);

        //defeated
        Vote::factory(['motion_id' => $m1a1->id])->affirmative()->count(3)->create();
        Vote::factory(['motion_id' => $m1a1->id])->negative()->count(7)->create();
        $m1a1->is_complete = true;
        $m1a1->save();


        //then the dog faction gets more direct
        //by removing not and praising the dog
        //strike and insert
        $m1a2 = Motion::create([
            'content' => "That the dog, who is very good, be given hamburgers",
            'requires' => 0.5,
            'meeting_id' => $meeting->id,
            'type' => 'amendment',
            'applies_to' => $main1->id
        ]);

        //If we're going to praise him, lets really make it clear why he deserves
        //burgers
        $m1a2a = Motion::create([
            'content' => "That the dog, who is the best dog ever, be given hamburgers",
            'requires' => 0.5,
            'meeting_id' => $meeting->id,
            'type' => 'amendment-secondary',
            'applies_to' => $m1a2->id
        ]);

        //Way too much discussion.
        $m1a2b = Motion::create([
            'content' => "That the pending question is called",
            'requires' => 0.66,
            'meeting_id' => $meeting->id,
            'type' => 'procedural-subsidiary',
            'applies_to' => $m1a2a->id
        ]);

        //question is called
        Vote::factory(['motion_id' => $m1a2b->id])->affirmative()->count(8)->create();
        Vote::factory(['motion_id' => $m1a2b->id])->negative()->count(2)->create();
        $m1a2b->is_complete = true;
        $m1a2b->save();
        $m1a2->markSuperseded($m1a2b);

        //Vote on the secondary
        Vote::factory(['motion_id' => $m1a2a->id])->affirmative()->count(8)->create();
        Vote::factory(['motion_id' => $m1a2a->id])->negative()->count(2)->create();
        $m1a2a->is_complete = true;
        $m1a2a->save();

        //now we have a problem.... the primary is replaced by the secondary
        //how do we represent that?
        //todo for now, just as another primary amendment
        $m1a3 = Motion::create([
            'content' => $m1a2a->content,
            'requires' => 0.5,
            'meeting_id' => $meeting->id,
            'type' => 'amendment',
            'applies_to' => $main1->id
        ]);
        //mark superseded
        $m1a1->markSuperseded($m1a3);

        //need to move on
        $m1a3a = Motion::create([
            'content' => "That the pending matter be tabled",
            'requires' => 0.5,
            'meeting_id' => $meeting->id,
            'type' => 'procedural-subsidiary',
            'applies_to' => $m1a3->id
        ]);

        //Vote on tabling
        Vote::factory(['motion_id' => $m1a3a->id])->affirmative()->count(8)->create();
        Vote::factory(['motion_id' => $m1a3a->id])->negative()->count(2)->create();
        $m1a3a->is_complete = true;
        $m1a3a->save();

        $main2 = Motion::create([
            'content' => "That the CSUN cats be invited to every Senate meeting",
            'requires' => 0.5,
            'meeting_id' => $meeting->id,
            'type' => 'main'
        ]);

        //Vote on main2
        Vote::factory(['motion_id' => $main2->id])->affirmative()->count(10)->create();
        Vote::factory(['motion_id' => $main2->id])->negative()->count(0)->create();
        $main2->is_complete = true;
        $main2->save();

        //back to the dog
        $m1a3b = Motion::create([
            'content' => "That the tabled matter pertaining to dogs and hamburgers be taken from the table",
            'requires' => 0.5,
            'meeting_id' => $meeting->id,
            'type' => 'procedural-subsidiary',
            'applies_to' => $m1a3->id
        ]);

        Vote::factory(['motion_id' => $m1a3b->id])->affirmative()->count(9)->create();
        Vote::factory(['motion_id' => $m1a3b->id])->negative()->count(1)->create();
        $m1a3b->is_complete = true;
        $m1a3b->save();

        //the amended amendment is adopted
        Vote::factory(['motion_id' => $m1a3->id])->affirmative()->count(9)->create();
        Vote::factory(['motion_id' => $m1a3->id])->negative()->count(1)->create();
        $m1a3->is_complete = true;
        $m1a3->save();
        //Now the main motion text is the amended text....

        $main3 = $repo->handleApprovedAmendment($main1, $m1a3);

        //finally we vote on the dog
        Vote::factory(['motion_id' => $main3->id])->affirmative()->count(6)->create();
        Vote::factory(['motion_id' => $main3->id])->negative()->count(4)->create();
        $main3->is_complete = true;
        $main3->save();

        //The currently pending motion
        Motion::create([
            'content' => "That the proposed curriculum regarding the study of tacos, especially pertaining to their deliciousness, be approved",
            'requires' => 0.5,
            'type' => 'main',
            'meeting_id' => $meeting->id
        ]);


        echo "Full meeting id: " . $meeting->id;
    }
}
