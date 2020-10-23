<?php


namespace App\LTI\Middleware;


use Closure;
use Illuminate\Http\Request;

/**
 * Class CheckRequiredLTIParams
 *
 * Probably not needed. Will handle with the request object
 *
 * @package App\LTI\Middleware
 */
class CheckRequiredLTIParams
{


    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        return $next($request);
    }

}
