<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Auction;

class ChatMessage extends Mailable
{
    use Queueable, SerializesModels;

    protected $text;
    protected $auction;

    public function __construct($text, Auction $auction)
    {
        $this->text = $text;
        $this->auction = $auction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('С вами пытались связаться на cross-contract.ru')
            ->view('mail.chat_message')->with([
                'text' => $this->text,
                'contragent' => $this->auction->contragent->title,
                'contragent_link' => 'http://project.cross-contract.ru/personal/contragents/show/' . $this->auction->contragent->id,
                'auction_link' => 'http://project.cross-contract.ru/personal/auctions/show/' . $this->auction->id
            ]);;
    }
}
