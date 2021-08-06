<?php

namespace App\Http\Controllers\Motion;

use App\Events\MotionMarkedInOrder;
use App\Events\MotionMarkedOutOfOrder;
use App\Events\MotionSeekingSecond;
use App\Http\Controllers\Controller;
use App\Models\Motion;
use App\Repositories\IMotionRepository;
use Illuminate\Http\Request;

class MotionOrderlinessController extends Controller
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


    public function markMotionInOrder(Motion $motion)
    {
        $this->setLoggedInUser();
        $this->authorize('canRuleOnOrderliness', $motion);
        $motion = $this->motionRepo->markInOrder($motion, $this->user);
        MotionMarkedInOrder::dispatch($motion);
        MotionSeekingSecond::dispatch($motion);
        return response()->json($motion);
    }

    public function markMotionOutOfOrder(Motion $motion)
    {
        $this->setLoggedInUser();
        $this->authorize('canRuleOnOrderliness', $motion);
        $motion = $this->motionRepo->markOutOfOrder($motion, $this->user);
        MotionMarkedOutOfOrder::dispatch($motion);
        return response()->json($motion);
    }

}
