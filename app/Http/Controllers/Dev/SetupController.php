<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Motion;
use Illuminate\Http\Request;

class SetupController extends Controller
{

    public function devView(Meeting $meeting){
        return view('dev.dev-setup', ['data' => ['meeting' => $meeting]]);
    }




}
