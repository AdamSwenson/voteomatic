<?php

namespace App\Http\Controllers\Motion;

use App\Events\MotionSeconded;
use App\Events\NoSecondObtained;
use App\Exceptions\IneligibleSecondAttempt;
use App\Http\Controllers\Controller;
use App\Models\Motion;
use App\Repositories\IMotionRepository;
use App\Repositories\IMotionStackRepository;
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
    /**
     * @var IMotionRepository|mixed
     */
    public $motionRepo;
    public $motionStackRepo;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('second-eligibility');

        $this->motionRepo = app()->make(IMotionRepository::class);
        $this->motionStackRepo = app()->make(IMotionStackRepository::class);
    }


    /**
     * @param Motion $motion
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function markMotionSeconded(Motion $motion)
    {
        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

        $this->authorize('secondMotion', $motion);
//        try {
        $this->motionRepo->secondMotion($motion, $this->user);

        //Broadcast the event
        MotionSeconded::dispatch($motion);

        //Set it as the currently pending motion
        $meeting = $motion->meeting;
        $this->motionStackRepo->setAsCurrentMotion($meeting, $motion);


        return response()->json($motion);

    }


    public function markNoSecondObtained(Motion $motion)
    {
        NoSecondObtained::dispatch($motion);
        return response()->json($motion);
    }

}
