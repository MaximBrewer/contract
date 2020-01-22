<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
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
        $auctions = Auction::where('multiplicity_id', $this->multiplicity->id)->where('product_id', $this->product->id)->get();
        $ids = [];
        foreach($auctions as $auction) $ids[] = $auction->id;
        return Bet::whereIn('auction_id', $ids)->sum('volume');
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
