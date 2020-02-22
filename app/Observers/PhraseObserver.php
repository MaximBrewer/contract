<?php

namespace App\Observers;

use App\Phrase;
use App\Dialogue;

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
