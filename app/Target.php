<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
    ];

    public function getFilledAttribute()
    {
        $this->store;
        $this->product;
        $this->multiplicity;
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
    
    public function contragent()
    {
        return $this->belongsTo('App\Contragent');
    }
    
    public function multiplicity()
    {
        return $this->belongsTo('App\Multiplicity');
    }
    
}
