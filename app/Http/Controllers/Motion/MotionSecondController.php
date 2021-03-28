<?php

namespace App\Http\Controllers\Motion;

use App\Http\Controllers\Controller;
use App\Models\Motion;
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
    public function __construct()
    {
        $this->middleware('auth');
      }


    /**
     * @param Motion $motion
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function markMotionSeconded(Motion $motion){
        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

        $this->authorize('secondMotion', $motion);

        $motion->seconded = true;
        $motion->save();
        return response()->json($motion);
    }

}
