<?php

namespace App\Http\Controllers\Guest;

use App\Http\Requests\WaitlistRequest;
use App\Models\WaitlistEntry;
use Illuminate\Http\Request;

use Illuminate\Routing\Controller as BaseController;

class WaitlistController extends BaseController
{

    public function __construct()
    {
        $this->middleware('guest');
    }


    public function show()
    {
        return view('waitlistForm');
    }

    public function addToWaitlist(WaitlistRequest $request)
    {
        $entry = WaitlistEntry::create($request->all());
        return redirect('/')->with('status', 'Thank you for your interest! You have been added to the waitlist.');
    }
}
