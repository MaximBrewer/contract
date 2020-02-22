<?php
use App\User;
use App\Dialogue;
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

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('survey.{survey_id}', function ($user, $survey_id) {
    return [
        'id' => $user->id
    ];
});

Broadcast::channel('dialog.{dialog}', function ($user, $dialog) {
    $d = Dialogue::find($dialog);
    $k = User::find($user->id)->contragents[0]->id;
    return $k == $d->contragent_1 || $k == $d->contragent_2;
});