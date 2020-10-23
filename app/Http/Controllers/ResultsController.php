<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class ResultsController
 * Handles reporting how many votes were yay /nay
 * @package App\Http\Controllers
 */
class ResultsController extends Controller
{


    public function getResults(Motion $motion){
        $data = [];

        return $motion;



    }
}
