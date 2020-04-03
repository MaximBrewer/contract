<?php

namespace App\Observers;

use App\Bet;
use App\Auction as AuctionModel;
use Illuminate\Support\Facades\DB;

class Auction
{
    /**
     * Handle the auction "created" event.
     *
     * @param  \App\Auction  $a
     * @return void
     */
    public function created(AuctionModel $a)
    {
    }

    /**
     * Handle the auction "updated" event.
     *
     * @param  \App\Auction  $a
     * @return void
     */
    public function updated(AuctionModel $a)
    {
        
    }


    public function updating(AuctionModel $a)
    {
        if ($a->isDirty('volume')) {
            $bets_vol_approved = Bet::where('auction_id', $a->id)->whereNotNull('approved_volume')->sum('volume');
            $freeVolume = $a->volume - $bets_vol_approved;

            if ($a->volume - $bets_vol_approved < 0) {
                $a->error[] = __('You can`t set volume less then was approved!');
                return false;
            }

            $bets = Bet::where('auction_id', $a->id)
                ->leftJoin('contragents', 'contragents.id', '=', 'bets.contragent_id')
                ->select('bets.*', 'contragents.rating')
                ->whereNull('approved_volume')
                ->orderBy('price', 'desc')
                ->orderBy('volume', 'desc')
                ->orderBy('rating', 'desc')
                ->orderBy('created_at', 'asc')
                ->get();


            foreach ($bets as $bet) {
                if ($freeVolume <= 0) {
                    $bet->delete();
                    continue;
                }
                if ($freeVolume >= $bet->volume) {
                    $freeVolume -= $bet->volume;
                } elseif ($bet->id) {
                    $bet->update(['volume' => $freeVolume]);
                    $freeVolume = 0;
                }
            }
        }
    }

    /**
     * Handle the auction "deleted" event.
     *
     * @param  \App\Auction  $a
     * @return void
     */
    public function deleting(AuctionModel $a)
    {
        $bets_sum = Bet::where('auction_id', $a->id)->whereNotNull('approved_volume')->sum('price');
        if ($bets_sum) return false;
        Bet::where('auction_id', $a->id)->delete();
    }

    /**
     * Handle the auction "restored" event.
     *
     * @param  \App\Auction  $a
     * @return void
     */
    public function restored(AuctionModel $a)
    {
        //
    }

    /**
     * Handle the auction "force deleted" event.
     *
     * @param  \App\Auction  $a
     * @return void
     */
    public function forceDeleted(AuctionModel $a)
    {
        Bet::where('auction_id', $a->id)->delete();
    }
}
