<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Http\Requests\LTIRequest;
use App\Repositories\IUserRepository;
use Database\Seeders\FakeFullMeetingSeeder;
use Illuminate\Http\Request;

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
        Log::debug($request);
//        abort(403);;
        dd($request);

        $seeder = new FakeFullMeetingSeeder();
        $meeting = $seeder->run();
        return response()->json($meeting);


    }

    public function launchMemberDemo(LTIRequest $request)
    {


    }


}
