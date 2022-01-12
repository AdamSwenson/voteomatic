<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isNull;

/**
 * If the specified meeting doesn't exist,
 * sends us back to the home page
 */
class CheckIfMeetingExists
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

        $meeting = $request->route()->parameter('meeting');
        if (isNull($meeting)) {
            $request->session()->flash('status', 'The meeting you are trying to access does not exist');
            redirect()->route('home');
        }


        return $next($request);
    }
}
