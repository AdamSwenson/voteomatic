<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DevController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function showMeeting(Meeting $meeting){
        return redirect()->route('meetingHome', $meeting);
    }

//    public function amendment(Motion $motion)
//    {
//
//        $meeting = Meeting::factory()->create();
//
//        return view('dev.dev-amendment', ['data' => [
//            'meeting' => $meeting,
//            'motion' => $motion]]);
//
//    }
//
//
//
//    public function tree(Meeting $motion)
//    {
//
//        return view('dev.dev-amendment', ['data' => [
//            'meeting' => $meeting,
//            'motion' => $motion]]);
//
//    }
}
