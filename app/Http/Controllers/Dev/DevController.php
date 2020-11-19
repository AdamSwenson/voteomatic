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
        $this->user = User::factory()->administrator()->create();
        Auth::login($this->user);
    }


    public function amendment(Motion $motion)
    {

        $meeting = Meeting::factory()->create();

        return view('dev.dev-amendment', ['data' => [
            'meeting' => $meeting,
            'motion' => $motion]]);

    }
}
