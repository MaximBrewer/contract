<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Contragent;

class ContragentMessage extends Mailable
{
    use Queueable, SerializesModels;

    protected $text;
    protected $contragent;

    public function __construct($text, Contragent $contragent)
    {
        $this->text = $text;
        $this->contragent = $contragent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('С вами пытались связаться на cross-contract.ru')
            ->view('mail.contragent_message')->with([
                'text' => $this->text,
                'contragent' => $this->contragent->title,
                'contragent_link' => 'http://project.cross-contract.ru/personal/contragents/show/' . $this->contragent->id
            ]);;
    }
}
