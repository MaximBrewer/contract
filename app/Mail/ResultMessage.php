<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Settlement;

class ResultMessage extends Mailable
{
    use Queueable, SerializesModels;

    protected $result;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($result)
    {
        $this->result = $result;
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $uids = Settlement::where('bet_id', $this->result->id)->get();
        if(count($uids)) $ulink = '<br><a href="http://project.cross-contract.ru/admin/settlements/7/edit' . $uids[0].'">Ссылка на расчет</a>';
        else $ulink = '';
        return $this->subject('Не согласен с расчетами!')
            ->view('mail.result_message')->with([
                'result' => $this->result,
                'link' => 'http://project.cross-contract.ru/admin/bets/' . $this->result->id,
                'ulink' => $ulink
            ]);;
    }
}
