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
        return '<?xml version="1.0" encoding="UTF-8"?>
<cartridge_basiclti_link xmlns="http://198.199.110.194/entry-test"
    xmlns:blti = "http://198.199.110.194/entry-test"
    <blti:launch_url>http://198.199.110.194/entry-test</blti:launch_url>
    <blti:extensions platform="canvas.instructure.com">
      <lticm:property name="privacy_level">public</lticm:property>
    </blti:extensions>
</cartridge_basiclti_link>';
    }
}
