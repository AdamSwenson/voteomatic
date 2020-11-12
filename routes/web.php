<?php

use App\Http\Controllers\Dev\EntryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LTI\LTIConfigController;
use App\Http\Controllers\LTI\LTILaunchController;
use App\Http\Controllers\Meeting\MeetingController;
use App\Http\Controllers\Meeting\RosterController;
use App\Http\Controllers\Motion\MotionController;
use App\Http\Controllers\Motion\MotionStackController;
use App\Http\Controllers\ReceiptValidationController;
use App\Http\Controllers\RecordVoteController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\Dev\SetupController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\VoteHistoryController;
use App\Http\Controllers\VotePageController;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/* =============================
        todo DEV ROUTES TO BE REMOVED IN PRODUCTION
   ============================= */
Route::get('/dev/testlog', [EntryController::class, 'logreturn']);
Route::get('/dev/test-results/{motion}', [ResultsController::class, 'devView']);
//can't test with dev/ since that messes up route root for resource urls
Route::get('/dev-test-setup', [SetupController::class, 'devView']);
Route::get('/entry/{motion}', [EntryController::class, 'handleLogin']);
Route::get('/entry-test', [EntryController::class, 'loginTest']);
//Route::post('/entry-test', '\App\Http\Controllers\EntryController@loginTest');

/* =============================
        Login, LTI authentication, and other admin
   ============================= */
Auth::routes();

// LTI access endpoint
Route::post('/entry-test', [LTILaunchController::class, 'handleLaunchRequest'])
    ->withoutMiddleware([ VerifyCsrfToken::class]);
//Route::post('/lti/{meeting}', 'LTILaunchController@handleLaunchRequest');
//unused
Route::get('/lticonfig', [LTIConfigController::class, 'lticonfig']);


// Index pages
Route::get('/', [HomeController::class, 'index']);
Route::get('/home/{meeting}', [HomeController::class, 'meetingIndex'])
    ->name('meetingHome');
Route::get('/home', [HomeController::class, 'index'])
    ->name('home');


/* =============================
        Main application pages
   ============================= */

//main page where votes get cast
//todo should probably rename all this since it's basically the application
Route::get('main/{motion}', [VotePageController::class, 'getVotePage'])
    ->name('main');


/* =============================
        Meetings
   ============================= */
Route::resource('meetings', MeetingController::class);
Route::get('roster/{meeting}', [RosterController::class, 'getRoster']);

/* =============================
        Motions
   ============================= */
Route::get('motions/meeting/{meeting}', [MotionController::class, 'getAllForMeeting']);
//Route::post('motions/meeting/{meeting}', [MotionController::class, 'createMotion']);
Route::post('motions/close/{motion}', [MotionStackController::class, 'markMotionComplete']);
Route::post('motions/stack/{meeting}/{motion}', [MotionStackController::class, 'setAsCurrentMotion']);
Route::get('motions/stack/{meeting}', [MotionStackController::class, 'getCurrentMotion']);
Route::resource('motions', MotionController::class);


/* =============================
        Individual votes and vote history controllers
   ============================= */
Route::get('/cast-votes/{meeting}', [VoteHistoryController::class, 'getPreviouslyCastVotes']);
//controller which handles validating and recording votes
Route::post('record-vote/{motion}', [RecordVoteController::class, 'recordVote'] );

Route::post('validation', [ReceiptValidationController::class, 'validateReceipt']);
Route::resource('votes', VoteController::class);


/* =============================
        Vote totals controllers
   ============================= */
Route::get('results/{motion}/counts', [ResultsController::class, 'getCounts']);
Route::get('results/{motion}', [ResultsController::class, 'getResults']);



/* =============================
        Resource and other service controllers
   ============================= */

//Route::get('meetings/{meeting}', [MeetingController::class, 'show']);
//Route::post('meetings/{meeting}', [MeetingController::class, 'update']);
//Route::get('meetings', [MeetingController::class, 'store']);





