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

    const DEV_USER_ID = 1;

    const DEV_NON_CHAIR_USER_ID = 2;

    const USE_CHAIR  = false;


    public function __construct(){
        $this->middleware('auth');

    }

    public function getUser()
    {

//
//        // TODO DEV REMOVE BEFORE ANY PRODUCTION USE
//        $env = env('APP_ENV');
//        if ($env != 'production' && $env != 'testing') {
//            //this is here in case I am dumb. it is not an excuse to be dumb
//            //and fail to remove before production.
//            if(self::USE_CHAIR){
//                Auth::loginUsingId(self::DEV_USER_ID, true);
//
//            }else{
//                Auth::loginUsingId(self::DEV_NON_CHAIR_USER_ID, true);
//            }
//
//        } else {
//
//            $this->middleware('auth');
//        }

        $this->user = Auth::user();
    }

}
