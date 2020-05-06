<?php

namespace App\Observers;

use App\Phrase;
use App\Dialogue;
use App\Mail\ContragentMessage;
use Illuminate\Support\Facades\Mail;
use App\User;
use Illuminate\Support\Facades\DB;

class PhraseObserver
{
    /**
     * Handle the phrase "created" event.
     *
     * @param  \App\Phrase  $phrase
     * @return void
     */
    public function created(Phrase $phrase)
    {
        //
        $dialogue = Dialogue::find($phrase->dialogue_id);
        $dialogue->update([
            'count' => ++$dialogue->count
        ]);

        $whom = $dialogue->contragents[0]->id == $phrase->contragent_id ? $dialogue->contragents[1] : $dialogue->contragents[0];
        $from = $dialogue->contragents[0]->id == $phrase->contragent_id ? $dialogue->contragents[0] : $dialogue->contragents[1];
        $userc = DB::table('user_contragent')->where('contragent_id', $whom->id)->select('user_id')->get();

        foreach ($userc as $userd) {
            $user = User::findOrFail($userd->user_id);
            Mail::to($user->email)->send(new ContragentMessage($phrase->text, $from));
        }
    }

    /**
     * Handle the phrase "updated" event.
     *
     * @param  \App\Phrase  $phrase
     * @return void
     */
    public function updated(Phrase $phrase)
    {
        //
    }

    /**
     * Handle the phrase "deleted" event.
     *
     * @param  \App\Phrase  $phrase
     * @return void
     */
    public function deleted(Phrase $phrase)
    {
        //
    }

    /**
     * Handle the phrase "restored" event.
     *
     * @param  \App\Phrase  $phrase
     * @return void
     */
    public function restored(Phrase $phrase)
    {
        //
    }

    /**
     * Handle the phrase "force deleted" event.
     *
     * @param  \App\Phrase  $phrase
     * @return void
     */
    public function forceDeleted(Phrase $phrase)
    {
        //
    }
}
