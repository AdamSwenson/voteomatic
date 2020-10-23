<?php

namespace App\Http\Controllers;

use App\Models\Motion;
use Illuminate\Http\Request;

/**
 * Class VotePageController
 * This is in charge of displaying the page where
 * people cast their votes
 * @package App\Http\Controllers
 */
class VotePageController extends Controller
{

    public function getVotePage(Motion $motion)
    {
        $data = [

            'data' => [
                'motion' => $motion
            ]
        ];

        return view('main', $data);
    }
}
