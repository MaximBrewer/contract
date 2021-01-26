<?php

namespace App\Observers;

use Illuminate\Support\Facades\Mail;
use App\Mail\ResultMessage;
use App\Settlement;
use App\Auction;
use App\Contragent;
use Illuminate\Support\Facades\DB;

use App\Bet as BetModel;

class Bet
{
    public function updating(BetModel $b)
    {
        if ($b->message && $b->isDirty('message')) {
            Mail::to('info@price2day.ru')->send(new ResultMessage($b));
        }
        if ($b->approved_contract && $b->isDirty('approved_contract')) {

            $settlement = Settlement::where('bet_id', $b->id)->where('type', 'invoice')->first();
            $auction = Auction::find($b->auction_id);
            $volume = $auction->multiplicity->coefficient * $b->volume;

            if (!$settlement) {
                $settlement = Settlement::create([
                    'bet_id' => $b->id,
                    'contragent_id' => $b->contragent_id,
                    'balance' => round($b->correct * $b->volume * (float)$auction->multiplicity->coefficient * $auction->prepay) / 100,
                    'method' => 'invoice',
                    'type' => 'credit',
                    'status' => 'processing'
                ]);
            } else {
                $settlement->update([
                    'balance' => round($b->correct * $b->volume * (float)$auction->multiplicity->coefficient * $auction->prepay) / 100
                ]);
            }

            $sum = $b->correct * $volume;
            $rest = ($b->correct - $auction->start_price) * $volume;
            $reward = 0.0005 * $sum + $rest * 0.05;

            $settlement = Settlement::where('bet_id', $b->id)->where('type', 'debit')->first();

            if ($settlement) {
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
    public function deleting(BetModel $b)
    {
        if ($settlement = Settlement::where('bet_id', $b->id)->first())
            $settlement->delete();
    }
}
