<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Dispute;

class DisputeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $dispute;

    public function __construct(Dispute $dispute)
    {
        $this->dispute = $dispute;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Приглашение на диспут между компаниями' . $this->dispute->contragents[0]->title . ' и ' . $this->dispute->contragents[1]->title)
            ->view('mail.dispute')->with([
                'dispute' => $this->dispute
            ]);
    }
}
