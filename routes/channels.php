<?php

use App\Models\Meeting;
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

/**
 * Messages to all members
 */
Broadcast::channel('meeting.{meetingId}', function ($user, $meetingId) {
    $meeting = Meeting::find($meetingId);
    return $meeting->isPartOfMeeting($user);
});

/**
 * Messages concerning a particular motion once it is set
 * as the motion being voted upon.
 */
Broadcast::channel('motions.{motionId}', function ($user, $motionId) {
    $motion = Motion::find($motionId);
    $meeting = $motion->meeting;
    return $meeting->isPartOfMeeting($user);
});

/**
 * Messages to the chair
 */
Broadcast::channel('chair.{meetingId}', function ($user, $meetingId) {
    $meeting = Meeting::find($meetingId);
    return $meeting->isPartOfMeeting($user);
});



//Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});
