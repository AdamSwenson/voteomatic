<?php

namespace App\Http\Controllers;

use App\Events\ForcePageReload;
use App\Events\NotifyPageRefreshNeeded;
use App\Models\Meeting;
use Illuminate\Http\Request;

class ForcedEventController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth');
    }


    public function forcePageReload(Meeting $meeting)
    {
        NotifyPageRefreshNeeded::dispatch($meeting);
        ForcePageReload::dispatch($meeting);
//    return ['dof'];
    }
}
