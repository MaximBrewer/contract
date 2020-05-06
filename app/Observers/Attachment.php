<?php

namespace App\Observers;


use App\Attachment as AttachmentModel;
use Illuminate\Support\Facades\DB;

class Attachment
{
    /**
     * Handle the auction "created" event.
     *
     * @param  \App\Auction  $a
     * @return void
     */
    public function created(AttachmentModel $a)
    {
    }

    /**
     * Handle the auction "updated" event.
     *
     * @param  \App\Auction  $a
     * @return void
     */
    public function updated(AttachmentModel $a)
    {
        
    }


    public function updating(AttachmentModel $a)
    {
    }

    /**
     * Handle the auction "deleted" event.
     *
     * @param  \App\Auction  $a
     * @return void
     */
    public function deleting(AttachmentModel $a)
    {
        @unlink(storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $a->url);
    }

    /**
     * Handle the auction "restored" event.
     *
     * @param  \App\Auction  $a
     * @return void
     */
    public function restored(AttachmentModel $a)
    {
        //
    }

    /**
     * Handle the auction "force deleted" event.
     *
     * @param  \App\Auction  $a
     * @return void
     */
    public function deleted(AttachmentModel $a)
    {
    }

    /**
     * Handle the auction "force deleted" event.
     *
     * @param  \App\Auction  $a
     * @return void
     */
    public function forceDeleted(AttachmentModel $a)
    {
    }
}
