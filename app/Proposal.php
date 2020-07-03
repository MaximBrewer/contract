<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    //
    protected $fillable = [
        'dispute_id',
        'contragent_id',
        'user_id',
        'message'
    ];
}
