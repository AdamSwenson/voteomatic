<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LTI\LTILaunchController;
use App\Http\Requests\LTIRequest;
use App\Repositories\IUserRepository;
use Database\Seeders\FakeFullMeetingSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class LTIDemoController
 *
 * Handles all demonstration modes which enter using LTI
 *
 * Demonstrations which use online login forms should be
 * handled with a WebloginDemoController
 *
 * @package App\Http\Controllers
 */
class LTIDemoController extends Controller
{
    //


    /**
     * @var IUserRepository|mixed
     */
    public $userRepository;

    public function __construct(){
//        $this->middleware('auth');

        $this->userRepository = app()->make(IUserRepository::class);

    }

    /**
     * Sets up a fake meeting and creates or makes the
     * user a chair
     *
     * @param LTIRequest $request
     */
    public function launchChairDemo(LTIRequest $request)
    {
        Log::debug("=========== LTIDemoController@launchChairDemo ===========");
        Log::debug($request);

        //Set up new meeting for them to play with.
        $seeder = new FakeFullMeetingSeeder();
        $meeting = $seeder->run();

        //Get an existing user or create a new person in the db
//        config(['app.is_chair_demo' => true]);
        $user = $this->userRepository->getUserFromRequest($request, $meeting);
        //Make them a chair
        $user->is_admin = true;
        $user->save();

        return redirect()->action([LTILaunchController::class, 'handleMeetingLaunchRequest'], [$meeting]);
//        return redirect()->route('lti-launch', $meeting);


    }

    public function launchMemberDemo(LTIRequest $request)
    {
        Log::debug("=========== LTIDemoController@launchMemberDemo ===========");
        Log::debug($request);

        //Set up new meeting for them to play with.
        $seeder = new FakeFullMeetingSeeder();
        $meeting = $seeder->run();

        //Get an existing user or create a new person in the db
        //and associate them with the meeting
        $user = $this->userRepository->getUserFromRequest($request, $meeting);


        return redirect()->route('lti-launch', $meeting);

    }


}
