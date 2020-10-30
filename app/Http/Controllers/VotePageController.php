<?php

namespace App\Http\Controllers;

use App\Models\Motion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class VotePageController
 *
 * todo  THIS HAS BASICALLY BECOME THE MAIN APP CONTROLLER. REFACTOR / RENAME ACCORDINGLY
 *
 * todo DEV BEFORE PUSHING TO PRODUCTION, REMOVE DEV AUTHENTICATION
 *
 * This is in charge of displaying the page where
 * people cast their votes
 * @package App\Http\Controllers
 */
class VotePageController extends Controller
{

    const DEV_USER_ID = 1;



    public function __construct()
    {

        // TODO DEV REMOVE BEFORE ANY PRODUCTION USE
        $env = env('APP_ENV');
        if ($env != 'production') {
            //this is here in case I am dumb. it is not an excuse to be dumb
            //and fail to remove before production.
            Auth::loginUsingId(self::DEV_USER_ID, true);
        }else{
            $this->middleware('auth');

        }

        $this->user = Auth::user();
    }

public function getVotePage(Motion $motion)
    {
        $data = [

            'data' => [
                'motion' => $motion
            ]
        ];

        return view('main', $data);
    }
}
