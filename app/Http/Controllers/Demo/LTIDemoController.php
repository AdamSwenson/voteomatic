<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LTI\LTILaunchController;
use App\Http\Requests\LTIRequest;
use App\LTI\Authenticators\AuthenticatorFactory;
use App\LTI\Exceptions\LTIAuthenticationException;
use App\LTI\LTI;
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
        Log::debug($request);
        try {
            //Set up new meeting for them to play with.
            $seeder = new FakeFullMeetingSeeder();
            $meeting = $seeder->run();

            $resourceLink = $this->LTIRepository->getResourceLinkFromRequest($request, $meeting);

            //We verify that the oath signature on the incoming post
            //request is valid
            $authenticator = AuthenticatorFactory::make($request);
            $authenticator->authenticate($request, $resourceLink);

            //Get an existing user or create a new person in the db
            $user = $this->userRepository->getUserFromRequest($request, $meeting);
            //Make them a chair
            $user->is_admin = true;
            $user->save();
            //Log them in
            Auth::login($user, true);

            //We redirect to the main app page
            return redirect()->route('meetingHome', $meeting->id);

        } catch (LTIAuthenticationException $e) {
            Log::debug($e);

            abort(403, 'Unauthorized action.');

        }

//        return redirect()->action([LTILaunchController::class, 'handleMeetingLaunchRequest'], [$meeting, $request]);
//        return redirect()->route('lti-launch', $meeting);


    }

    public function launchMemberDemo(LTIRequest $request)
    {
        Log::debug("=========== LTIDemoController@launchMemberDemo ===========");
        Log::debug($request);

        try {
            //Set up new meeting for them to play with.
            $seeder = new FakeFullMeetingSeeder();
            $meeting = $seeder->run();

            $resourceLink = $this->LTIRepository->getResourceLinkFromRequest($request, $meeting);

            //We verify that the oath signature on the incoming post
            //request is valid
            $authenticator = AuthenticatorFactory::make($request);
            $authenticator->authenticate($request, $resourceLink);

            //Get an existing user or create a new person in the db
            //and associate them with the meeting
            $user = $this->userRepository->getUserFromRequest($request, $meeting);

            //Make them not a chair
            $user->is_admin = false;
            $user->save();

            //Log them in
            Auth::login($user, true);

            //We redirect to the main app page
            return redirect()->route('meetingHome', $meeting->id);

            //return redirect()->route('lti-launch', $meeting);

        } catch (LTIAuthenticationException $e) {
            Log::debug($e);

            abort(403, 'Unauthorized action.');
        }
    }


}
