<?php

namespace App\Exceptions;

use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (ClientVisibleException $e, $request) {
            return response()->json($e, $e->status);
        });

//        $this->reportable(/**
//         * @param BroadcastException $e
//         */ function (BroadcastException $e) {
//             abort(443);
//dd($e);
//             throw new PageRefreshNeededException();



//        });

//        $this->renderable(function (DoubleVoteAttempt $e, $request) {
//            return response()->json($e, DoubleVoteAttempt::ERROR_CODE);
//        });
    }
}
