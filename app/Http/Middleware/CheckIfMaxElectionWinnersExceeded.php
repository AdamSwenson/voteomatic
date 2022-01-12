<?php

namespace App\Http\Middleware;

use App\Exceptions\ExcessCandidatesSelected;
use App\Models\Motion;
use Closure;
use Illuminate\Http\Request;

/**
 * Class CheckIfMaxElectionWinnersExceeded
 *
 * Used on elections to make sure the voter has not
 * selected more winners than is allowed.
 *
 * @package App\Http\Middleware
 */
class CheckIfMaxElectionWinnersExceeded
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
        $motion = $request->route()->parameter('motion');

        //dev This was added in VOT-69 due to some very strange behavior
        // namely, the motion object would be retrieved when doing a regular meeting but
        // this was just the string id for an election.
        // ...turns out it was due to naming the motion variable $office when it was injected
        // into the controller. WTF laravel?
//        if(! $motion instanceof Motion){
//            $motion = Motion::find($motion);
//        }

        $totalSelected = sizeof($request->candidateIds) + sizeof($request->writeIns);

        if($totalSelected > $motion->max_winners){
            throw new ExcessCandidatesSelected();
//            abort(ExcessCandidatesSelected::ERROR_CODE, ExcessCandidatesSelected::MESSAGE);
        }

        return $next($request);
    }
}
