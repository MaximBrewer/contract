<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewInvoiceReceiver extends Mailable
{
    use Queueable, SerializesModels;

    protected $settlement;
    protected $recipient;
    protected $receiver;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($settlement, $receiver, $recipient)
    {
        //
        $this->settlement = $settlement;
        $this->receiver = $receiver;
        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Вам выставлен счет')->view('mail.new_invoice_receiver')->with([
            'settlement' => $this->settlement,
            'receiver' => $this->receiver,
            'recipient' => $this->recipient,
        ]);
    }
}
