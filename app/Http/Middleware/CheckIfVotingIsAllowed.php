<?php

namespace App\Http\Middleware;

use App\Exceptions\VoteSubmittedWhenNotAllowed;
use App\Models\Motion;
use Closure;
use Illuminate\Http\Request;

/**
 * A second (in addition to checking is_complete) way of
 * determining whether the user may vote on the motion.
 *
 * This is needed because setting is_complete causes results to be
 * shown. In cases where, e.g., a vote needs to be aborted, this can reject a vote attempt.
 */
class CheckIfVotingIsAllowed
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

        $motion = $request->route()->parameter('motion');

        //dev This was added in VOT-69 due to some very strange behavior
        // namely, the motion object would be retrieved when doing a regular meeting but
        // this was just the string id for an election. No idea why that was happening....
        if(! $motion instanceof Motion){
            $motion = Motion::find($motion);
        }

        //Don't apply this to elections
        if (! $motion->meeting->is_election && ! $motion->is_voting_allowed) {
            throw new VoteSubmittedWhenNotAllowed($motion);
        }

        return $next($request);
    }
}
