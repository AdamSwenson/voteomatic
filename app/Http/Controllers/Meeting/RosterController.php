<?php

namespace App\Http\Controllers\Meeting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RosterController extends Controller
{
    //

    public function __construct()
    {


        $this->middleware('auth');

    }


    /**
     * Gets all users associated with the meeting
     *
     * todo Not sure whether this is needed
     *
     * @param Meeting $meeting
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoster(Meeting $meeting){
        $users = $meeting->users()->all();
        return response()->json($users);
    }

}
