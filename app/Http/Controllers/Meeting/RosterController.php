<?php

namespace App\Http\Controllers\Meeting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RosterController extends Controller
{
    //

    public function __construct()
    {
        // TODO DEV ENSURE THE TEST HARNESS USER WAS REMOVED BEFORE ANY PRODUCTION USE
        $this->getUser();

    }


    /**
     * Gets all users associated with the meeting
     * @param Meeting $meeting
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoster(Meeting $meeting){
        $users = $meeting->users()->all();
        return response()->json($users);
    }

}
