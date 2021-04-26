<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{

    protected $fillable = [
        'volume',
        'contragent_id',
        'auction_id',
        'interval_id',
        'price',
        'approved_volume',
        'approved_contract',
        'took_part',
        'distributor_id',
        'can_bet',
        'correct',
        'store_id',
        'guarantee',
        'message',
        'self',
    ];

    protected $appends = [
        'contragent',
        'distributor'
    ];

    public function getContragentAttribute()
    {

        $contragent = \App\Contragent::findOrFail($this->contragent_id);
        return [
            'id' => $contragent->id,
            'title' => $contragent->title,
        ];
    }

    public function getDistributorAttribute()
    {
        $distributor = \App\Contragent::find($this->distributor_id);
        return $distributor ? $distributor->title : "";
    }

    public function store()
    {
        return $this->belongsTo('App\Store');
    }


    public function auction()
    {
        return $this->belongsTo('App\Auction');
    }
}
