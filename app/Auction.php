<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;
use App\Store;
use App\Multiplicity;
use App\Contragent;


class Auction extends Model
{

    protected $appends = [
        'filled',
    ];

    public function getFilledAttribute()
    {
        $this->store;
        $this->product;
        $this->multiplicity;
        $this->contragent;
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
    
    public function multiplicity()
    {
        return $this->belongsTo('App\Multiplicity');
    }
    
    public function contragent()
    {
        return $this->belongsTo('App\Contragent');
    }
    
}
