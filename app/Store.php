<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Store extends Model
{
    protected $fillable = [
        'coords',
        'address',
        'contragent_id'
    ];
}
