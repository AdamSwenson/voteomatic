<?php

namespace App\Http\Middleware;

use App\Exceptions\DoubleVoteAttempt;
use App\Models\Vote;
use App\Repositories\IVoterEligibilityRepository;
use Closure;
use Illuminate\Http\Request;

class CheckVoterEligibility
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
        $motion = Motion::find($request->motion_id);
        $voterEligibilityRepo = app()->make(IVoterEligibilityRepository::class);

        try {
            $voterEligibilityRepo->isEligible($motion, $user);

            return $next($request);

        } catch (DoubleVoteAttempt  $e) {
            abort($e->errorCode);
            //todo error message
        }


    }
}
