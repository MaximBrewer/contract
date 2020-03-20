<?php

namespace App\Observers;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResultMessage;

use App\Bet as BetModel;

class Bet
{
    public function updating(BetModel $b)
    {
        if ($b->message && $b->isDirty('message')) {
            Mail::to('info@price2day.ru')->send(new ResultMessage($b));
        }
    }

}
