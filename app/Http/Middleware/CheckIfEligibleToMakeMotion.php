<?php

namespace App\Http\Middleware;

use App\Exceptions\IneligibleMotionCreator;
use App\Models\Meeting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfEligibleToMakeMotion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->isMethod('post')) {
            return $next($request);
        }

        $user = Auth::user();
        $meeting = Meeting::find($request->meetingId);
        if($meeting->isOwner($user)){
            //owner/chair can do what they want
            return $next($request);
        }

        //User is not the owner
        $membersMakeMotions = $meeting->settingStore->getSetting('members_make_motions');
        if($membersMakeMotions === true || $membersMakeMotions === 1){
            return $next($request);
        }

        throw new IneligibleMotionCreator();


    }
}
