<?php

namespace App\Http\Controllers;

use App\Http\Requests\LTIRequest;
use App\LTI\Authenticators\AuthenticatorFactory;
use App\LTI\Exceptions\LTIAuthenticationException;
use App\LTI\LTI;
use App\Models\Meeting;
use App\Models\ResourceLink;
use App\Models\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

//use App\Providers\LTIServiceProvider;


/**
 * Class LTILaunchController
 * Handles requests for the xml configuration
 * to launch LTI.
 *
 * @package App\Http\Controllers
 */
class LTILaunchController extends Controller
{

    /**
     * @var LTI
     */
    public $lti;

    public function __construct()
    {
        //create an lti object to use by instantiating via
        //the lti service provider
        $this->lti = app()->make(LTI::class);
    }


    /**
     * Receives the launch request
     *
     * lti:create-tool-consumer
     *  key: tacokey
     *  name: taco
     * secret: nom
     * @param LTIRequest $request
     * @return void
     */
    public function handleLaunchRequest(LTIRequest $request)
    {
        $j = $request->all();
        Log::debug($request);

        //Check if the activity is enabled and reject access if not
        //todo Do this (later) or maybe add as middleware

        //The LTIRequest object has already checked that the needed
        //fields are populated.
        try {

            //We verify that the oath signature on the incoming post
            //request is valid
            $resourceLink = ResourceLink::where(['resource_link_id' => $request->resource_link_id])
                ->firstOrFail();
            //todo error handling if not found

            $authenticator = AuthenticatorFactory::make($request);
            $authenticator->authenticate($request, $resourceLink);

            //Now we look up the student based on the provided user id
            //todo consider whether want to just add anyone who passes the oath hurdle or if want whitelisted students (if latter, this would be firstOrFail)
//            $user = User::where(['lms_id' => $request->user_id])
//                ->firstOrFail();
            $user = User::factory()->create();

            //we log them in via the usual laravel means
            //todo refactor this whole process to fit the laravel authentication patterns and utilities
            Auth::login($user, true);

            //We redirect to the activity page
            return redirect()->route('meetingHome', $resourceLink->meeting);

        } catch (LTIAuthenticationException $e) {
            Log::debug($e);

            abort(403, 'Unauthorized action.');
            //todo logging and error handling here

        }

    }


//    /**
//     * Receives the launch request
//     *
//     * lti:create-tool-consumer
//     *  key: tacokey
//     *  name: taco
//     * secret: nom
//     * @param LTIRequest $request
//     * @return void
//     */
//    public function handleLaunchRequest(LTIRequest $request, Meeting $meeting)
//    {
//        //Check if the activity is enabled and reject access if not
//        //todo Do this (later) or maybe add as middleware
//
//        //The LTIRequest object has already checked that the needed
//        //fields are populated.
//        try {
//
//            //We verify that the oath signature on the incoming post
//            //request is valid
//            $resourceLink = ResourceLink::where(['id' => $request->resource_link_id])
//                ->firstOrFail();
//            //todo error handling if not found
//
//            $authenticator = AuthenticatorFactory::make($request);
//            $authenticator->authenticate($request, $resourceLink);
//
//            //Now we look up the student based on the provided user id
//            //todo consider whether want to just add anyone who passes the oath hurdle or if want whitelisted students (if latter, this would be firstOrFail)
//            $user = User::where(['lms_id' => $request->user_id])
//                ->firstOrFail();
//
//            //we log them in via the usual laravel means
//            //todo refactor this whole process to fit the laravel authentication patterns and utilities
//            Auth::login($user, true);
//
//            //We redirect to the activity page
//            return redirect()->route('meetingHome', [$meeting]);
//
//        } catch (LTIAuthenticationException $e) {
//            Log::debug($e);
//
//            abort(403, 'Unauthorized action.');
//            //todo logging and error handling here
//
//        }
//
//    }

    /**
     * Receives the launch request
     *
     * lti:create-tool-consumer
     *  key: tacokey
     *  name: taco
     * secret: nom
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function handleLaunchRequestUsingLibrary(Request $request)
    {
//        //push incoming data into global post array
//        //so lti can operate on it.
//        $this->populatePostArray($request);
//
//        $this->populateServerArray($request);
//
//        $this->lti->toolProvider()->handleRequest();
//
//
//        if ($this->lti->toolProvider()->ok !== true) {
//            $t = 'nom3';
//            dump($t);
//        }
////        dd($prov->resourceLink);
////        dd($prov);
////        $LTIToolProvider->handleRequest();
////dd($request);
////        dd(LTI::toolProvider()->reason);
//
////        return response('sdf', 200);
//
////        return response()->json([
////            'name' => 'Abigail',
////            'state' => 'CA',
////        ]);
//
//        return response('j', 200);

    }

    /**
     * The library handles authentication in the old school way
     * so we need to take the hopefully clean request and
     * shove it into the global array.
     * @param Request $request
     * @todo Long term: think through whether worth rewriting the library class is worthwhile.
     */
    public function populatePostArray(Request $request): void
    {
        foreach ($request->all() as $k => $v) {
            //The library handles authentication in the old school way
            //so we need to take the hopefully clean request and
            //shove it into the global array
            $_POST[$k] = $v;
        }
    }

    public function populateServerArray(Request $request): void
    {
        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
        $_SERVER['HTTP_HOST'] = 'www.test.com';
        $_SERVER['REQUEST_URI'] = '/test';
        $_SERVER['REQUEST_METHOD'] = 'POST';

    }
}
