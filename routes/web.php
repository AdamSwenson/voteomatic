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



Route::get('/', function () {
    return view('welcome');
});


Route::get('/entry/{motion}', '\App\Http\Controllers\EntryController@handleLogin');


//main page where votes get cast
Route::get('voter-page/{motion}', 'App\Http\Controllers\VotePageController@getVotePage');



//controller which handles validating and recording votes
Route::post('record-vote/{motion}', '\App\Http\Controllers\RecordVoteController@recordVote' );


Route::get('results/{motion}', '\App\Http\Controllers\ResultsController@getResults');

Route::resource('votes', VoteController::class);

Route::resource('motions', MotionController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
