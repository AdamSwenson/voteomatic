<?php

namespace App\Http\Controllers\Motion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class MotionSecondController
 *
 * todo dev making this it's own controller in case expand second stuff to optionally record user or timestamps
 *
 * @package App\Http\Controllers\Motion
 */
class MotionSecondController extends Controller
{
    //


    public function markMotionSeconded(Motion $motion){
        $motion->seconded = true;
        $motion->save();
        return $motion;
    }

}
