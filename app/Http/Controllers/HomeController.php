<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class HomeController
 *
 * Handles requests for main pages available to
 * authenticated users.
 *
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * NB, this is not the public index page. That's
     * handled by Guest/PublicIndexController
     *
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $this->setLoggedInUser();

        return view('home', ['name' => $this->user->name, 'uidHash' =>$this->user->userIdHash]);
    }



}
