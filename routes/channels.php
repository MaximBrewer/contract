<?php
use App\User;
use App\Dialogue;
use App\DialogueContragent;
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
    $dialog = Dialogue::find($dialog);
    $dialogContragents = DialogueContragent::where('dialogue_id', $dialog->id)->orderBy('id', 'asc')->get();
    $contragent_ids = [
        $dialogContragents[0]->contragent_id,
        $dialogContragents[1]->contragent_id,
    ];
    return in_array(User::find($user->id)->contragents[0]->id, $contragent_ids);
});