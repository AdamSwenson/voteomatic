<?php

use App\Http\Controllers\MeetingController;
use App\Http\Controllers\MotionController;
use App\Http\Controllers\ReceiptValidationController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\VoteController;
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
Route::get('/dev/testlog', '\App\Http\Controllers\EntryController@logreturn');
Route::get('/dev/test-results/{motion}', [ResultsController::class, 'devView']);
//can't test with dev/ since that messes up route root for resource urls
Route::get('/dev-test-setup', [SetupController::class, 'devView']);


/* =============================
        Login, LTI authentication, and other admin
   ============================= */

Auth::routes();

// LTI access endpoint
Route::post('/entry-test', '\App\Http\Controllers\LTILaunchController@handleLaunchRequest')->withoutMiddleware([ VerifyCsrfToken::class]);
//Route::post('/lti/{meeting}', 'LTILaunchController@handleLaunchRequest');


Route::get('/entry/{motion}', '\App\Http\Controllers\EntryController@handleLogin');
Route::get('/entry-test', '\App\Http\Controllers\EntryController@loginTest');
//Route::post('/entry-test', '\App\Http\Controllers\EntryController@loginTest');

Route::get('/lticonfig', '\App\Http\Controllers\EntryController@lticonfig');



// Index pages
Route::get('/', function () {
    return view('home');
});

Route::get('/home/{meeting}', [App\Http\Controllers\HomeController::class, 'meetingIndex'])->name('meetingHome');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



/* =============================
        Main application pages
   ============================= */

//main page where votes get cast
Route::get('voter-page/{motion}', 'App\Http\Controllers\VotePageController@getVotePage');


/* =============================
        Resource and other service controllers
   ============================= */

//controller which handles validating and recording votes
Route::post('record-vote/{motion}', '\App\Http\Controllers\RecordVoteController@recordVote' );


Route::get('results/{motion}/counts', '\App\Http\Controllers\ResultsController@getCounts');
Route::get('results/{motion}', '\App\Http\Controllers\ResultsController@getResults');

Route::post('validation', '\App\Http\Controllers\ReceiptValidationController@validateReceipt');


//Route::get('meetings/{meeting}', [MeetingController::class, 'show']);
//Route::post('meetings/{meeting}', [MeetingController::class, 'update']);
//Route::get('meetings', [MeetingController::class, 'store']);

Route::resource('meetings', MeetingController::class);
Route::resource('motions', MotionController::class);
Route::resource('votes', VoteController::class);


