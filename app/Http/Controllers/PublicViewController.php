<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PublicViewController extends Controller
{
    //


    public function __construct()
    {
//        $this->middleware('guest');
        $email = config('auth.public_user_email');
        $user = User::where('email', $email)->first();
        Auth::login($user);

    }

    public function publicHome(Meeting $meeting){
        //Log the fact that it has been viewed!
        Log::debug("=========== PublicViewController@publicHome =========== \n " . \request());
//        Log::debug(request());

//        dd($meeting);
        $data = [

            'data' => [
                'meeting_id' => $meeting->id,

                'isElection' => false,

                'isAdmin' => false,

                'meeting' => $meeting
            ]
        ];

        return view('pmodeGuest', $data);

    }
}
