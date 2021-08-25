<?php

namespace App\Http\Middleware;

use App\Exceptions\DoubleVoteAttempt;
use App\Repositories\IVoterEligibilityRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Motion;

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

        //dev This was added in VOT-69 due to some very strange behavior
        // namely, the motion object would be retrieved when doing a regular meeting but
        // this was just the string id for an election.
        // ...turns out it was due to naming the motion variable $office when it was injected
        // into the controller. WTF laravel?
//        if(! $motion instanceof Motion){
//            $motion = Motion::find($motion);
//        }


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
