<?php


namespace App\LTI\Middleware;


use Closure;

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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

}