<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Motion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class MainController
 *
 * This is in charge of displaying the main application page
 *
 * @package App\Http\Controllers
 */
class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('meeting-exists');
    }


    public function meetingHome(Meeting $meeting){
        $this->setLoggedInUser();

        $this->authorize('view', $meeting);

        $data = [

            'data' => [
                'meeting_id' => $meeting->id,

                'isElection' => $meeting->is_election,

                'isAdmin' => $this->user->is_admin,

                'meeting' => $meeting
            ]
        ];

        return view('main', $data);

    }

//
//    /**
//     * dev Is this still needed? I think this is deprecated. See VOT-55 which worked around a problem this caused.
//     * @deprecated
//     * @param Motion $motion
//     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
//     * @throws \Illuminate\Auth\Access\AuthorizationException
//     */
//    public function getVotePage(Motion $motion)
//    {
//
//        //Don't understand why this can't be in the constructor. But it can't
//        $this->setLoggedInUser();
//
//        $this->authorize('view', $motion);
//
//        $data = [
//
//            'data' => [
//                'isAdmin' => $this->user->is_admin,
//                'motion' => $motion
//            ]
//        ];
//
//        return view('main', $data);
//    }
}
