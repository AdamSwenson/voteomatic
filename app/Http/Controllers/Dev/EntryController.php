<?php

namespace App\Http\Controllers\Dev;

use App\Models\Motion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EntryController extends Controller
{
    //\


    public function handleLogin(Motion $motion, Request $request)
    {
        return view('entry', ['data' => $request]);
    }

    public function loginTest(Request $request)
    {
        Log::debug($request);
        return view('entry', ['data' => $request]);
    }
//
//    public function lticonfig(Request $request)
//    {
//        Log::debug($request->all());
//
//        return response(file_get_contents(resource_path('lticonfig.xml')), 200, [
//            'Content-Type' => 'application/xml'
//        ]);
//
////        return view('lticonfig');
//    }

    //todo SUPER DEV. REMOVE!!!!!!!!!!!!!!!!!!!
    public function logreturn(){

         return response(file_get_contents(storage_path('logs/laravel.log')), 200, [
            'Content-Type' => 'text/text'
        ]);
    }
}
