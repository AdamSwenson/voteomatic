<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicViewController extends Controller
{
    //


    public function __construct()
    {
//        $this->middleware('guest');
        $email = config('auth.public_user_email');
        $user = User::where('email', $email)->first();
        Auth::login($user);

        //Log the fact that it has been viewed!

    }

    public function publicHome(Meeting $meeting){
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
