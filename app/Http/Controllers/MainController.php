<?php

namespace App\Http\Controllers;

use App\Models\Motion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class VotePageController
 *
 *
 * This is in charge of displaying the page where
 * people cast their votes
 * @package App\Http\Controllers
 */
class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getVotePage(Motion $motion)
    {

        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();
dd($motion);

        $this->authorize('view', $motion);

        $data = [

            'data' => [
                'isAdmin' => $this->user->is_admin,
                'motion' => $motion
            ]
        ];

        return view('main', $data);
    }
}
