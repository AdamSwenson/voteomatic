<?php

use App\Models\Motion;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
Broadcast::channel('motions', function ($user, $motionId) {

//Broadcast::channel('motions.{motionId}', function ($user, $motionId) {
//    dd('stopped');
    return true;
    $motion = Motion::find($motionId);
    $meeting = $motion->meeting;
    return $meeting->isPartOfMeeting($user);
});
//Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});
