<?php

namespace App\Http\Middleware;

use App\Exceptions\DoubleVoteAttempt;
use App\Exceptions\VoteSubmittedAfterMotionClosed;
use App\Models\Motion;
use Closure;
use Illuminate\Http\Request;

class CheckIfMotionClosed
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $motion = $request->route()->parameter('motion');

        //dev This was added in VOT-69 due to some very strange behavior
        // namely, the motion object would be retrieved when doing a regular meeting but
        // this was just the string id for an election. No idea why that was happening....
        if(! $motion instanceof Motion){
            $motion = Motion::find($motion);
        }

        if ($motion->is_complete) {
            throw new VoteSubmittedAfterMotionClosed($motion);
//            abort(VoteSubmittedAfterMotionClosed::ERROR_CODE, VoteSubmittedAfterMotionClosed::MESSAGE);
        }

        return $next($request);
    }

}
