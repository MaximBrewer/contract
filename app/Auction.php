<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;
use App\Store;
use App\Multiplicity;
use Illuminate\Support\Facades\DB;
use App\Contragent;
use Illuminate\Support\Facades\Auth;


class Auction extends Model
{


    protected $fillable = [
        'contragent_id',
        'store_id',
        'comment',
        'start_at',
        'finish_at',
        'product_id',
        'multiplicity_id',
        'step',
        'start_price',
        'volume',
        'confirmed',
        'finished',
        'started'
    ];

    protected $appends = [
        'filled',
        'bidder',
        'bidders',
        'free_volume'
    ];


    public function getFreeVolumeAttribute()
    {
        $cnt = DB::select('select sum(volume) as busy_volume from bets where auction_id = ?', [$this->id]);
        return (int) $this->volume - (int) $cnt[0]->busy_volume;
    }

    public function getStartAtAttribute($value)
    {
        return date(DATE_ATOM, strtotime($value));
    }


    public function getFinishAtAttribute($value)
    {
        return date(DATE_ATOM, strtotime($value));
    }

    public function getFilledAttribute()
    {
        $this->store;
        $this->bidder;
        $this->product;
        $this->multiplicity;
        $this->contragent;
        $this->bets;
        return true;
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function store()
    {
        return $this->belongsTo('App\Store');
    }

    public function bets()
    {
        return $this->hasMany('App\Bet')->orderBy('created_at', 'desc');
    }

    public function multiplicity()
    {
        return $this->belongsTo('App\Multiplicity');
    }

    public function contragent()
    {
        return $this->belongsTo('App\Contragent');
    }

    public function getBiddersAttribute()
    {

        $bidderIds = DB::select('select contragent_id from contragent_auction where auction_id = ?', [$this->id]);
        $bidderIdsArray = [];
        foreach ($bidderIds as $bidderId) $bidderIdsArray[] = $bidderId->contragent_id;
        $contragents = Contragent::whereIn('id', $bidderIdsArray)->select('id', 'title', 'fio', 'phone')->get();
        $cAgents = [];
        foreach ($contragents as $contragent) {
            $cAgents[$contragent->id] = [
                'id' => $contragent->id,
                'title' => $contragent->title, 
                'fio' => $contragent->fio, 
                'phone' => $contragent->phone
            ];
        }
        return $cAgents;
    }

    public function getBidderAttribute()
    {
        if (Auth::user() && count(Auth::user()->contragents)) {
            foreach (Auth::user()->contragents[0]->auctions as $auction) {
                if ($auction->id == $this->id) return 1;
            }
        }
        return 0;
    }
}
