<?php

namespace App\Providers;

use App\LTI\LTI;
use App\LTI\ServiceProvider;

/**
 * Class LTIServiceProvider
 *
 * Provides access to the LTI class which handles
 *handles lti logins
 *
 * @package App\Providers
 */
class LTIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LTI::class, function ($app) {
            return new LTI();
        });
    }
//
//    /**
//     * Bootstrap services.
//     *
//     * @return void
//     */
//    public function boot()
//    {
//        //
//    }
}
