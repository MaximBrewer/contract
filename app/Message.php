<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'user_id',
        'contragent_id',
        'auction_id',
        'message',
        'username'
    ];
}
