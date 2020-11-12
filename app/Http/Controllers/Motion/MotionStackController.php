<?php

namespace App\Http\Controllers\Motion;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Motion;
use App\Repositories\IMotionStackRepository;
use Illuminate\Http\Request;

/**
 * Handles the order of motions
 *
 * Class MotionStackController
 * @package App\Http\Controllers\Motion
 */
class MotionStackController extends Controller
{
    /**
     * @var IMotionStackRepository|mixed
     */
    public $motionStackRepo;

    public function __construct()
    {

        $this->middleware('auth');

        $this->motionStackRepo = app()->make(IMotionStackRepository::class);

    }


    public function markMotionComplete(Motion $motion)
    {

        $motion->is_complete = true;
        $motion->save();

        return response()->json($motion);

    }


    /**
     * Returns the motion at the top of the pending queue
     * @param Meeting $meeting
     */
    public function getCurrentMotion(Meeting $meeting)
    {
        $result = $this->motionStackRepo->getCurrentMotion($meeting);
        return response()->json($result);
    }


    public function setAsCurrentMotion(Meeting $meeting, Motion $motion)
    {
        $result = $this->motionStackRepo->setAsCurrentMotion($meeting, $motion);
        return response()->json($result);
    }

}
