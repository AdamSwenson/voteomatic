<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        /**
         * This allows us to pass the incoming request directly between controllers
         * without using the redirect function (which makes it hard to update state)
         * From https://jeffochoa.me/redirect-a-request-to-a-specific-route-laravel
         */
        Route::macro(
            'sendToRoute',
            function (Request $request, string $routeName) {
                $route = tap($this->routes->getByName($routeName))->bind($request);

                $this->current = $route;

                return $this->runRoute($request, $this->current);
            }
        );
    }
}
