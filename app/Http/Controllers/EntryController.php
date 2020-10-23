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
}
