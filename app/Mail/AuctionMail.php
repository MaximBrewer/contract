<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Auction;

class AuctionMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
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
        return $this->subject('Приглашение от '.$this->auction->contragent->title.' на еженедельное распределение продукции '. $this->auction->product->title)
            ->view('mail.auction')->with([
                'text' => $this->text,
                'link' => 'https://project.cross-contract.ru/personal/auctions/show/' . $this->auction->id
            ]);;
    }
}
