<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');


        // TODO DEV ENSURE THE TEST HARNESS USER WAS REMOVED BEFORE ANY PRODUCTION USE
//        $this->getUser();

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
//        $this->user = Auth::user();
        $this->getUser();


        return view('home', ['name' => $this->user->name, 'uidHash' =>$this->user->userIdHash]);
    }


    public function meetingIndex(Meeting $meeting){
        $this->getUser();

        $data = [

            'data' => [
                'meeting_id' => $meeting->id,

                'isAdmin' => $this->user->is_admin,
            ]
        ];

        return view('main', $data);


//        return view('home', ['user' => $this->user, 'name' => $this->user->name, 'uidHash' =>$this->user->userIdHash]);

//        return view('home');
    }
}
