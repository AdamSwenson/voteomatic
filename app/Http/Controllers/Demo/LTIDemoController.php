<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LTI\LTILaunchController;
use App\Http\Requests\LTIRequest;
use App\LTI\Authenticators\AuthenticatorFactory;
use App\LTI\Exceptions\LTIAuthenticationException;
use App\LTI\LTI;
use App\Models\Meeting;
use App\Repositories\ILTIRepository;
use App\Repositories\IUserRepository;
use Database\Seeders\FakeFullMeetingSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    /**
     * @var ILTIRepository|mixed
     */
    public $LTIRepository;
    /**
     * @var LTI|mixed
     */
    public $lti;

    public function __construct()
    {
//        $this->middleware('auth');


        //create an lti object to use by instantiating via
        //the lti service provider
        $this->lti = app()->make(LTI::class);

        //we do this here since type hinting messes with the tests
        $this->LTIRepository = app()->make(ILTIRepository::class);
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
        Log::debug("[" . env('APP_ENV'). "]");
        Log::debug($request);
        try {
            //Set up new meeting for them to play with.
            $meeting = Meeting::factory()->create();

            //Authenticate the incoming request
            $this->LTIRepository->handleMeetingLaunchRequest($request, $meeting);

            //Get an existing user or create a new person in the db
            $user = $this->userRepository->getUserFromRequest($request, $meeting);

            //Make them a chair
            $user->is_admin = true;
            $user->save();

            //make them the owner of the meeting
            $meeting->setOwner($user);

            //Log them in
            Auth::login($user, true);

            //Finally we populate the meeting with motions and votes. We do this here
            //so that the the user can be added as a voter on past motions.
            $seeder = new FakeFullMeetingSeeder();
            $seeder->run($meeting, $user);

            //We redirect to the main app page
            //dev Fixing problem introduced in 15562eb
            return redirect()->route('main', $meeting->id);

        } catch (LTIAuthenticationException $e) {
            Log::debug($e);

            abort(403, 'Unauthorized action.');

        }

    }

    public function launchMemberDemo(LTIRequest $request)
    {
        Log::debug("=========== LTIDemoController@launchMemberDemo ===========");
        Log::debug("[" . env('APP_ENV'). "]");
        Log::debug($request);

        try {
            //Set up new meeting for them to play with.
            $meeting = Meeting::factory()->create();

            //Authenticate the incoming request
            $this->LTIRepository->handleMeetingLaunchRequest($request, $meeting);

            //Get an existing user or create a new person in the db
            //and associate them with the meeting
            $user = $this->userRepository->getUserFromRequest($request, $meeting);

            //Make them not a chair
            $user->is_admin = false;
            $user->save();

            //Log them in
            Auth::login($user, true);

            //Finally we populate the meeting with motions and votes. We do this here
            //so that the the user can be added as a voter on past motions.
            $seeder = new FakeFullMeetingSeeder();
            $seeder->run($meeting, $user);

            //We redirect to the main app page
            //dev Fixing problem introduced in 15562eb
            return redirect()->route('main', $meeting->id);

        } catch (LTIAuthenticationException $e) {
            Log::debug($e);

            abort(403, 'Unauthorized action.');
        }
    }


}
