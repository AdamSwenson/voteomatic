<?php

namespace App\Http\Middleware;

use App\Exceptions\BadWriteInAttempt;
use App\Exceptions\DoubleVoteAttempt;
use App\Repositories\IVoterEligibilityRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Motion;
use Illuminate\Support\Facades\Validator;

class ValidateWriteIn
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

        $validator = Validator::make($request->all(), [
            'first_name' => ['required'],
            'last_name' => ['required']
        ]);

        if ($validator->fails()) {
            throw new BadWriteInAttempt($motion);
        }

        return $next($request);

    }
}
