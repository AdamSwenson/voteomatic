<?php

namespace App\Http\Controllers\Guest;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class PublicIndexController extends BaseController
{

    public function __construct()
    {
        $this->middleware('guest');
    }


    public function index(){
        return view('welcome');
    }

}
