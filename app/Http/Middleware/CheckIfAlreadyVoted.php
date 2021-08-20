<?php

namespace App\Http\Middleware;

use App\Exceptions\DoubleVoteAttempt;
use App\Repositories\IVoterEligibilityRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfAlreadyVoted
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
        $user = Auth::user();
        $motion = $request->route()->parameter('motion');
        $voterEligibilityRepo = app()->make(IVoterEligibilityRepository::class);

//        try {
        if ($voterEligibilityRepo->hasAlreadyVoted($motion, $user)) {

            throw new DoubleVoteAttempt($motion);

//            abort(DoubleVoteAttempt::ERROR_CODE, DoubleVoteAttempt::MESSAGE);
        }

        return $next($request);
//
//        } catch (DoubleVoteAttempt  $e) {
//            abort($e::ERROR_CODE, $e::MESSAGE);
//        }


    }
}
