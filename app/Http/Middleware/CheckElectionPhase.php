<?php

namespace App\Http\Middleware;

use App\Exceptions\ElectionPhaseProhibition;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckElectionPhase
{

    public function checkAdmin($meeting)
    {
        //Admin checks
        switch ($meeting->electionPhase) {
            case 'setup':
                break;
            case 'nominations':
                break;

            case 'voting':
                break;

            case 'closed':
                break;

            case 'results':
                break;

            default:
        }
        return true;
    }

    public function checkRegUser($meeting)
    {
        //Regular user checks
        switch ($meeting->electionPhase) {
            case 'setup':
                //They should not be able to get any data
                throw new ElectionPhaseProhibition();
                break;
            case 'nominations':
                throw new ElectionPhaseProhibition();
                break;
            case 'voting':
                break;
            case 'closed':
                //They should not be able to get any data
                //This will not prevent them from logging in and
                //seeing the 'election closed' message.
                throw new ElectionPhaseProhibition();
                break;
            case 'results':
//                throw new ElectionPhaseProhibition();
                break;

            default:

        }
        return true;
    }


    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws ElectionPhaseProhibition
     */
    public function handle(Request $request, Closure $next)
    {
        $motion = $request->route()->parameter('motion');
        $meeting = $motion->meeting;
        $user = Auth::user();

        if ($meeting->is_election) {
            if ($user->is_admin) {
                $this->checkAdmin($meeting);
            } else {
                $this->checkRegUser($meeting);
            }
        }

        return $next($request);
    }
}
