<?php

namespace App\Http\Controllers\Meeting;

use App\Events\ForcePageReload;
use App\Http\Controllers\Controller;
use App\Models\Meeting;

class MeetingChairController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Uses pusher to request that all connected users browsers
     * reload the page
     * @param Meeting $meeting
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function forceUsersToReload(Meeting $meeting)
    {
        //Do auth
        $this->setLoggedInUser();
        $this->authorize('update', $meeting);

        ForcePageReload::dispatch($meeting);

        return response()->json(200);
    }
}
