<?php

namespace App\Http\Controllers\Motion;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Motion;
use Illuminate\Http\Request;

/**
 * Handles the order of motions
 *
 * Class MotionStackController
 * @package App\Http\Controllers\Motion
 */
class MotionStackController extends Controller
{
    public function __construct(){
        // TODO DEV ENSURE THE TEST HARNESS USER WAS REMOVED BEFORE ANY PRODUCTION USE
        $this->getUser();

    }


    public function markMotionComplete(Motion $motion){
        $motion->is_complete = true;
        $motion->save();

        return response()->json($motion);

    }


    /**
     * Returns the motion at the top of the pending queue
     * @param Meeting $meeting
     */
    public function getCurrentMotion(Meeting $meeting){

    }


}
