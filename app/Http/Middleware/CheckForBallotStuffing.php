<?php

namespace App\Http\Middleware;

use App\Exceptions\BallotStuffingAttempt;
use Closure;
use Illuminate\Http\Request;

/**
 * This checks a ballot to make sure that
 * no candidate is voted for more than once.
 * (The possibility of creating a different candidate
 * who duplicates an official candidate via write in was already
 * checked when the write in was created)
 */
class CheckForBallotStuffing
{

    /**
     * Utility function to make testing and
     * future exception cases easier
     * @param $request
     * @return bool
     */
    public function containsDuplicates($request)
    {
        return collect($request->candidateIds)->duplicates()->count() > 0;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->containsDuplicates($request)) {
            $motion = $request->route()->parameter('motion');
            throw new BallotStuffingAttempt($motion);
        }

        return $next($request);
    }
}
