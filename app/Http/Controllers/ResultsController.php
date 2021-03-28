<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResultsRequest;
use App\Models\Motion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

/**
 * Class ResultsController
 * Handles reporting how many votes were yay /nay
 * @package App\Http\Controllers
 */
class ResultsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function devView(Motion $motion){
        return view('dev.dev-results', ['data' => ['motion' => $motion]]);
    }

    /**
     * @param Motion $motion
     * @param ResultsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCounts(Motion $motion, ResultsRequest $request){
        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

        $this->authorize('viewMotionResults', $motion);

        /*
         * We aren't going to just send the motion object
         * because we don't necessarily want everyone seeing
         * the counts if they look at the traffic.
         * This is something to be sorted out elsewhere too
         *
         * Note: the client already has the initial motion object
         * which defines the content, requirement, et cetera so
         * we don't need to send it again.
         */
        $data = [
//            'passed' => $motion->passed,
//            /*
//             */
//            'totalVotes' => $motion->totalVotesCast
        ];

            $data['yayCount'] = count($motion->affirmativeVotes);
            $data['nayCount'] = count($motion->negativeVotes);

        return Response::json($data);

    }

    /**
     * @param Motion $motion
     * @param ResultsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getResults(Motion $motion, ResultsRequest $request){

        //Don't understand why this can't be in the constructor. But it can't
        $this->setLoggedInUser();

        $this->authorize('viewMotionResults', $motion);

        /*
         * We aren't going to just send the motion object
         * because we don't necessarily want everyone seeing
         * the counts if they look at the traffic.
         * This is something to be sorted out elsewhere too
         *
         * Note: the client already has the initial motion object
         * which defines the content, requirement, et cetera so
         * we don't need to send it again.
         */
        $data = [
            'passed' => $motion->passed,
            /*
             */
            'totalVotes' => $motion->totalVotesCast
        ];

        return Response::json($data);

    }
}
