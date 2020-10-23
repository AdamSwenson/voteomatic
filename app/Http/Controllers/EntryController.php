<?php

namespace App\Http\Controllers;

use App\Models\Motion;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    //\


    public function handleLogin(Motion $motion, Request $request)
    {
        return view('entry', ['data' => $request]);
    }

    public function loginTest(Request $request)
    {
        return view('entry', ['data' => $request]);
    }

    public function lticonfig(Request $request)
    {

        return response(file_get_contents(resource_path('lticonfig.xml')), 200, [
            'Content-Type' => 'application/xml'
        ]);

//        return view('lticonfig');
    }
}
