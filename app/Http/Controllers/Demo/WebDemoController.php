<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Http\Requests\LTIRequest;
use App\LTI\Exceptions\LTIAuthenticationException;
use App\Models\Meeting;
use Database\Seeders\FakeFullMeetingSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WebDemoController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }


    public function launchChairDemo(){

        Log::debug("=========== LTIWebDemoController@launchChairDemo ===========");
        Log::debug("[" . env('APP_ENV'). "]");

//        try {
            //Set up new meeting for them to play with.
            $meeting = Meeting::factory()->create();

            $this->setLoggedInUser();

            //Make them a chair
            $this->user->is_admin = true;
        //dev Not saving it to avoid the referenced risk
        // SECURITY RISK: ANYONE WHO USES THIS GETS CHAIR STATUS EVERYWHERE
//            $this->user->save();

            $meeting->addUserToMeeting($this->user);
            //make them the owner of the meeting
            $meeting->setOwner($this->user);


            //Finally we populate the meeting with motions and votes. We do this here
            //so that the the user can be added as a voter on past motions.
            $seeder = new FakeFullMeetingSeeder();
            $seeder->run($meeting, $this->user);

            //We redirect to the main app page
            //dev Fixing problem introduced in 15562eb
            return redirect()->route('meetingHome', $meeting->id);

//        } catch (LTIAuthenticationException $e) {
//            Log::debug($e);
//
//            abort(408, 'Error in LTI authentication.');
//
//        }

    }

    public function launchMemberDemo(LTIRequest $request){

        abort(404);


    }

}
