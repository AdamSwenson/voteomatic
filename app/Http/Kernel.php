<?php

namespace App\Http;

use App\Exceptions\VoteSubmittedAfterMotionClosed;
use App\Http\Middleware\CheckElectionPhase;
use App\Http\Middleware\CheckForBallotStuffing;
use App\Http\Middleware\CheckIfAlreadyVoted;
use App\Http\Middleware\CheckIfEligibleToSecond;
use App\Http\Middleware\CheckIfMaxElectionWinnersExceeded;
use App\Http\Middleware\CheckIfMeetingExists;
use App\Http\Middleware\CheckIfEligibleToMakeMotion;
use App\Http\Middleware\CheckIfMotionClosed;
use App\Http\Middleware\CheckVoterEligibility;
use App\Http\Middleware\CheckWriteInDoesNotDuplicateOfficialCandidate;
use App\Http\Middleware\ValidateWriteInName;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
             \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        'vote-eligibility' => CheckVoterEligibility::class,
        'motion-closed' => CheckIfMotionClosed::class,
        'motion-make-eligibility' => CheckIfEligibleToMakeMotion::class,
        'previously-voted' => CheckIfAlreadyVoted::class,
        'second-eligibility' => CheckIfEligibleToSecond::class,
        'meeting-exists' => CheckIfMeetingExists::class,

        //election specific
        'excess-candidates-selected' => CheckIfMaxElectionWinnersExceeded::class,
        'validate-write-in-name' => ValidateWriteInName::class,
        'check-write-in-does-not-duplicate-official' => CheckWriteInDoesNotDuplicateOfficialCandidate::class,
        'check-for-ballot-stuffing' => CheckForBallotStuffing::class,
        'check-election-phase' => CheckElectionPhase::class
    ];
}
