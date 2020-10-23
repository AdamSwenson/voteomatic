<?php

use App\Http\Controllers\MotionController;
use App\Http\Controllers\VoteController;
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
Route::get('/testlog', '\App\Http\Controllers\EntryController@logreturn');



/* =============================
        Login, LTI authentication, and other admin
   ============================= */

Auth::routes();

// LTI access endpoint
Route::post('/entry-test', '\App\Http\Controllers\LTILaunchController@handleLaunchRequest');
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


Route::get('results/{motion}', '\App\Http\Controllers\ResultsController@getResults');


Route::resource('meetings', \App\Http\Controllers\MeetingController::class);
Route::resource('motions', MotionController::class);
Route::resource('votes', VoteController::class);


