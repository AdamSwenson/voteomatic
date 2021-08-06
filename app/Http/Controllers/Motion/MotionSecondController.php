<?php

namespace App\Http\Controllers\Motion;

use App\Events\MotionSeconded;
use App\Events\NoSecondObtained;
use App\Exceptions\IneligibleSecondAttempt;
use App\Http\Controllers\Controller;
use App\Models\Motion;
use App\Repositories\IMotionRepository;
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

    public function __construct()
    {
        $this->middleware('auth');
        $this->motionRepo = app()->make(IMotionRepository::class);
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
        try {
            $this->motionRepo->secondMotion($motion, $this->user);

            //Broadcast the event
            MotionSeconded::dispatch($motion);

            return response()->json($motion);
        } catch (IneligibleSecondAttempt $e) {
            //todo
            //Tell them that they can't second their own motion
        }

    }


    public function markNoSecondObtained(Motion $motion)
    {
        NoSecondObtained::dispatch($motion);
        return response()->json();
    }

}
