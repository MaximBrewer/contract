<?php

namespace App\Observers;

use Illuminate\Support\Facades\Mail;
use App\Mail\ResultMessage;
use App\Settlement;
use App\Auction;
use App\Contragent;

use App\Bet as BetModel;

class Bet
{
    public function updating(BetModel $b)
    {
        if ($b->message && $b->isDirty('message')) {
            Mail::to('info@price2day.ru')->send(new ResultMessage($b));
        }
        if ($b->approved_contract && $b->isDirty('approved_contract')) {

            $auction = Auction::find($b->auction_id);
            $volume = $auction->multiplicity->coefficient * $b->volume;
            $sum = $b->correct * $volume;
            $rest = ($b->correct - $auction->start_price) * $volume;
            $reward = 0.0005 * $sum + $rest * 0.05;

            $settlements = Settlement::where('bet_id', $b->id)->get();

            if (count($settlements)) {
                $settlement = $settlements[0];
                $settlement->update([
                    'contragent_id' => $b->auction->contragent_id,
                    'bet_id' => $b->id,
                    'balance' => $reward,
                    'type' => 'debit',
                    'status' => 'done',
                ]);
            } else
                Settlement::create([
                    'contragent_id' => $b->auction->contragent_id,
                    'bet_id' => $b->id,
                    'balance' => $reward,
                    'type' => 'debit',
                    'status' => 'done',
                ]);
        }
    }
}
