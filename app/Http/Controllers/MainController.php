<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Motion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class VotePageController
 *
 *
 * This is in charge of displaying the page where
 * people cast their votes
 * @package App\Http\Controllers
 */
class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function meetingHome(Meeting $meeting){
        $this->setLoggedInUser();

        //todo Probably need to enable this after April meeting See VOT-41
//        $this->authorize('view', $meeting);


        $data = [

            'data' => [
                'meeting_id' => $meeting->id,

                'isAdmin' => $this->user->is_admin,
            ]
        ];

        return view('main', $data);

    }


    /**
     * dev Is this still needed? See VOT-55 which worked around a problem this caused.
     * @param Motion $motion
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getVotePage(Motion $motion)
    {

        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

        $this->authorize('view', $motion);

        $data = [

            'data' => [
                'isAdmin' => $this->user->is_admin,
                'motion' => $motion
            ]
        ];

        return view('main', $data);
    }
}
