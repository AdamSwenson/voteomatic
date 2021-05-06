<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Demo\LTIDemoController;
use App\Http\Controllers\Demo\WebDemoController;
use App\Http\Controllers\Dev\DevController;
use App\Http\Controllers\Dev\EntryController;
use App\Http\Controllers\Election\CandidateController;
use App\Http\Controllers\Election\ElectionController;
use App\Http\Controllers\Election\ElectionResultsController;
use App\Http\Controllers\Election\CandidatePoolController;
use App\Http\Controllers\Election\ElectionVoteController;
use App\Http\Controllers\Election\OfficeController;
use App\Http\Controllers\Election\PersonController;
use App\Http\Controllers\Guest\PublicIndexController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LTI\LTIConfigController;
use App\Http\Controllers\LTI\LTILaunchController;
use App\Http\Controllers\Meeting\MeetingController;
use App\Http\Controllers\Meeting\RosterController;
use App\Http\Controllers\Motion\MotionController;
use App\Http\Controllers\Motion\MotionSecondController;
use App\Http\Controllers\Motion\MotionStackController;
use App\Http\Controllers\Motion\MotionTemplateController;
use App\Http\Controllers\ReceiptValidationController;
use App\Http\Controllers\RecordVoteController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\Dev\SetupController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\VoteHistoryController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\Guest\WaitlistController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Auth;
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
//Route::post('taco', [LTIDemoController::class, 'launchChairDemo'])
//    ->withoutMiddleware([ VerifyCsrfToken::class]);

/* =============================
        todo DEV ROUTES TO BE REMOVED IN PRODUCTION
   ============================= */
//Route::get('/dev/testlog', [EntryController::class, 'logreturn']);
//Route::get('/dev/test-results/{motion}', [ResultsController::class, 'devView']);
////can't test with dev/ since that messes up route root for resource urls
//Route::get('/dev-test-setup', [SetupController::class, 'devView']);
//Route::get('/entry/{motion}', [EntryController::class, 'handleLogin']);
//Route::get('/entry-test', [EntryController::class, 'loginTest']);
////Route::post('/entry-test', '\App\Http\Controllers\EntryController@loginTest');
//Route::get('/dev/amendment/{motion}', [DevController::class, 'amendment']);
//Route::get('/dev/tree/{meeting}', [DevController::class, 'tree']);

Route::get('dev/meeting/{meeting}', [DevController::class, 'showMeeting']);

/* =============================
        Login, LTI authentication, and other admin
   ============================= */

//See VOT-29 for why this can't be used.
//Auth::routes();

//Login (Currently only used by administrator)
Route::post('login', [LoginController::class, 'login']);
// Logout Route
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


// LTI access endpoint
//Route::post('/entry-test', [LTILaunchController::class, 'handleLaunchRequest'])
//    ->withoutMiddleware([ VerifyCsrfToken::class]);

Route::post('/lti-entry/{meeting}', [LTILaunchController::class, 'handleMeetingLaunchRequest'])
    ->withoutMiddleware([ VerifyCsrfToken::class])
    ->name('lti-launch');

//unused
//Route::get('/lti/config', [LTIConfigController::class, 'lticonfig']);


/* =============================
        Demo mode
   ============================= */
Route::post('lti/chair-demo', [LTIDemoController::class, 'launchChairDemo'])
    ->withoutMiddleware([ VerifyCsrfToken::class]);
Route::post('lti/member-demo', [LTIDemoController::class, 'launchMemberDemo'])
    ->withoutMiddleware([ VerifyCsrfToken::class]);

Route::post('web/chair-demo', [WebDemoController::class, 'launchChairDemo'])
    ->withoutMiddleware([ VerifyCsrfToken::class]);
Route::post('web/member-demo', [WebDemoController::class, 'launchMemberDemo'])
    ->withoutMiddleware([ VerifyCsrfToken::class]);


/* =============================
        Election
   ============================= */
//Route::post('election/{motion}/candidates/{candidate}', [CandidateController::class, 'update']);
//Route::post('election/{motion}/candidates', [CandidateController::class, 'store']);

Route::get('election/{motion}/candidates', [CandidateController::class, 'getOfficialCandidatesForOffice']);
Route::get('election/{motion}/results', [ElectionResultsController::class, 'getResults']);
//Route::resource('election/candidate/{motion}', CandidateController::class);
//Route::resource('election/{meeting}', )
Route::post('election/vote/{motion}', [ElectionVoteController::class, 'recordVote']);

//setup office/position
Route::post('election/setup/{meeting}/office', [OfficeController::class, 'store']);

Route::post('election/setup/office/{motion}', [OfficeController::class, 'store']);
Route::resource('elections', ElectionController::class);
Route::resource('offices', OfficeController::class);

//pool of eligible nominees for office
Route::get('election/pool/{motion}', [CandidatePoolController::class, 'getCandidatePool']);
Route::post('election/pool/{motion}/{person}', [CandidatePoolController::class, 'addPersonToPool']);

Route::post('election/nominate/{poolMember}', [CandidateController::class, 'addCandidateToBallot']);

Route::resource('election/people', PersonController::class);

Route::post('election/write-in/{motion}', [CandidateController::class, 'addWriteInCandidate']);

//Handles update and destroy
Route::delete('election/candidate/{candidate}', [CandidateController::class, 'removeCandidate']);






/* =============================
        Main application pages
   ============================= */
//Internal landing page after non-lti login
Route::get('/home', [HomeController::class, 'index'])
    ->name('home');
//If no meeting specified, should also take to the internal home page
Route::get('main', [HomeController::class, 'index'] );

//Internal landing page after lti login
Route::get('/home/{meeting}', [MainController::class, 'meetingHome'])
    ->name('meetingHome');


//main page where votes get cast
Route::get('main/{motion}', [MainController::class, 'getVotePage'])
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
Route::post('motions/second/{motion}', [MotionSecondController::class, 'markMotionSeconded']);
Route::get('motions/templates', [MotionTemplateController::class, 'getTemplates']);
Route::get('motions/types', [MotionTemplateController::class, 'getMotionTypes']);
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
        Publicly accessible
   ============================= */
// Public index
Route::get('/', [PublicIndexController::class, 'index'])
    ->name('index');
// Waitlist
Route::get('/waitlist', [WaitlistController::class, 'show'])
    ->name('waitlist');
Route::post('/waitlist', [WaitlistController::class, 'addToWaitlist']);



/* =============================
        Resource and other service controllers
   ============================= */

//Route::get('meetings/{meeting}', [MeetingController::class, 'show']);
//Route::post('meetings/{meeting}', [MeetingController::class, 'update']);
//Route::get('meetings', [MeetingController::class, 'store']);



