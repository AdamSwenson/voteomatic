<?php

namespace App\Http\Middleware;

use App\Exceptions\VoteSubmittedAfterMotionClosed;
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

        if ($motion->is_complete) {
            abort(VoteSubmittedAfterMotionClosed::ERROR_CODE, VoteSubmittedAfterMotionClosed::MESSAGE);
        }


        return $next($request);
    }
}
