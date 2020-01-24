<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{

    protected $fillable = [
        'volume',
        'contragent_id',
        'auction_id',
        'price',
        'approved_volume',
        'approved_contract',
        'took_part',
        'can_bet',
        'correct',
        'store_id',
    ];

    protected $appends = [
        'contragent',
    ];

    public function getContragentAttribute(){

        $contragent = \App\Contragent::findOrFail($this->contragent_id);
        return [
            'id' => $contragent->id,
            'title' => $contragent->title,
        ];
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
