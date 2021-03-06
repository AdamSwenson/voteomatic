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
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getVotePage(Motion $motion)
    {
        $this->getUser();

        $data = [

            'data' => [
                'isAdmin' => $this->user->is_admin,
                'motion' => $motion
            ]
        ];

        return view('main', $data);
    }
}
