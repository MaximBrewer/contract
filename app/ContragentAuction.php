<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContragentAuction extends Model
{
    protected $fillable = [
        'contragent_id',
        'auction_id',
        'can_bet',
        'observer'
    ];
    protected $table = 'contragent_auction';
    //
}
