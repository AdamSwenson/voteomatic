<?php

namespace App\Http\Middleware;

use App\Repositories\Election\ICandidateRepository;
use Closure;
use Illuminate\Http\Request;

class CheckWriteInDoesNotDuplicateOfficialCandidate
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
        $candidateRepo = app()->make(ICandidateRepository::class);

        //This will throw a BallotStuffingAttempt exception if invalid
        $candidateRepo->checkForDuplication($request->first_name, $request->last_name, $request->info, $motion);

        return $next($request);
    }

}
