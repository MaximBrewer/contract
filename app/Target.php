<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Bet;

class Target extends Model
{
    protected $fillable = [
        'product_id',
        'multiplicity_id',
        'contragent_id',
        'store_id',
        'volume',
        'remain'
    ];

    protected $appends = [
        'filled',
        'restof'
    ];

    public function getFilledAttribute()
    {
        $this->store;
        $this->product;
        $this->contragent;
        $this->multiplicity;
        return true;
    }

    public function getRestofAttribute()
    {

        $auctionIds = DB::select('select auction_id, can_bet from contragent_auction where can_bet = 1 and contragent_id = ?', [User::find(Auth::user()->id)->contragents[0]->id]);
        $auctionIdsArray = [];
        foreach ($auctionIds as $auctionId) $auctionIdsArray[] = $auctionId->auction_id;


        $auctions = Auction::whereIn('id', $auctionIdsArray)
            ->where('multiplicity_id', $this->multiplicity->id)
            ->where('product_id', $this->product->id)
            ->get();
        $ids = [];

        foreach ($auctions as $auction) {

            $ids[] = $auction->id;
        }
        if($this->store)
        $rest = Bet::whereIn('auction_id', $ids)->where('store_id', $this->store->id)->sum('volume');



        return $rest > $this->volume ? $this->volume : $rest;
    }


    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function store()
    {
        return $this->belongsTo('App\Store');
    }

    public function contragent()
    {
        return $this->belongsTo('App\Contragent');
    }

    public function multiplicity()
    {
        return $this->belongsTo('App\Multiplicity');
    }
}
