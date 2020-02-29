<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    
    public function auctions()
    {
        return $this->belongsToMany('App\Auction');
    }
}
