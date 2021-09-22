<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\Motion;
use App\Models\RecordedVoteRecord;
use App\Models\User;
use App\Models\Vote;
use App\Repositories\IMotionRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class FakeFullMeetingSeeder extends Seeder
{

    /**
     * Creates the full meeting with motions and votes.
     *
     * NB, it will add the user(s) to the meeting. Ownership of the
     * meeting must be handled elsewhere. That allows this to be used
     * for all user categories.
     *
     * @param null $meeting
     * @param null $user
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function run($meeting=null, $user=null)
    {
//        $voters = User::factory()->count(FakeFullMeetingSeeder::NUMBER_VOTERS)->create();

        $repo  = app()->make(IMotionRepository::class);

        //use the meeting provided or create a fresh one
        $meeting = ! is_null($meeting) ? $meeting   : Meeting::factory()->create();

        //we will need them later to be voters

        if(! is_null($user)){
            $realUsers = [$user];
            Log::debug('adding current user');

        }else{
            Log::debug('adding all users');
            //todo This seems like it will add all users, not just the current person
            $realUsers = User::all();
//        $realUsers = [
//            User::where('email', env('DEV_USER_ADMIN_EMAIL'))->first(),
//            User::where('email', env('DEV_USER_REGULAR_EMAIL'))->first()
//        ];
            foreach($realUsers as $user){
                $meeting->addUserToMeeting($user);
            }

        }


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
            'applies_to' => $m1a2a->id,
            'debatable' => false
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
            'applies_to' => $m1a3->id,
            'debatable' => false
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
            'applies_to' => $m1a3->id,
            'debatable' => false
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


        //Add our real users as voters on motions up to this point
        foreach($meeting->motions()->get() as $motion){
            foreach($realUsers as $user) {
                //using factory so won't have to enable mass assignment for creation
                RecordedVoteRecord::factory()->create([
                    'motion_id' => $motion->id,
                    'user_id' => $user->id
                ]);
            }
        }

        //The currently pending motion
        Motion::create([
            'content' => "That the proposed curriculum regarding the study of tacos, especially pertaining to their deliciousness, be approved",
            'requires' => 0.5,
            'type' => 'main',
            'is_current' => true,
            'meeting_id' => $meeting->id
        ]);


        echo "\nFull meeting id: " . $meeting->id . "\n";
        Log::debug('FakeFullMeetingSeeder: seeded meeting \n' . $meeting);

        return $meeting;
    }
}
