<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Http\Requests\LTIRequest;
use Illuminate\Http\Request;

class WebDemoController extends Controller
{
    //

    public function launchChairDemo(LTIRequest $request){

abort(404);

    }

    public function launchMemberDemo(LTIRequest $request){

        abort(404);


    }

}