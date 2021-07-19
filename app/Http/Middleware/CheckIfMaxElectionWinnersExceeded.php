<?php

namespace App\Http\Middleware;

use App\Exceptions\ExcessCandidatesSelected;
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

        $totalSelected = sizeof($request->candidateIds) + sizeof($request->writeIns);

        if($totalSelected > $motion->max_winners){
            abort(ExcessCandidatesSelected::ERROR_CODE, ExcessCandidatesSelected::MESSAGE);
        }

        return $next($request);
    }
}
