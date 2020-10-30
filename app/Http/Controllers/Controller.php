<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


public function getUser(){

    // TODO DEV REMOVE BEFORE ANY PRODUCTION USE
    $env = env('APP_ENV');
    if ($env != 'production') {
        //this is here in case I am dumb. it is not an excuse to be dumb
        //and fail to remove before production.
        Auth::loginUsingId(1, true);
    }else {


        $this->middleware('auth');
    }
    $this->user = Auth::user();
}

}
