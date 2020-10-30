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


        // TODO DEV ENSURE THE TEST HARNESS USER WAS REMOVED BEFORE ANY PRODUCTION USE
        $this->getUser();


    }

    public function getVotePage(Motion $motion)
    {
        $data = [

            'data' => [
                'isAdmin' => $this->user->isAdmin,
                'motion' => $motion
            ]
        ];

        return view('main', $data);
    }
}
