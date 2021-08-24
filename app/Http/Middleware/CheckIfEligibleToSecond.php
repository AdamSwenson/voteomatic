<?php

namespace App\Http\Middleware;

use App\Exceptions\DoubleVoteAttempt;
use App\Exceptions\IneligibleSecondAttempt;
use App\Repositories\IVoterEligibilityRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfEligibleToSecond
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $motion = $request->route()->parameter('motion');

        if (!$motion->isEligibleToSecond($user)) {
            throw new IneligibleSecondAttempt($motion);
        }
        //
        return $next($request);
    }
}
