<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;
use App\Store;
use App\Multiplicity;
use App\Contragent;


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
        'start_price',
        'volume'
    ];

    protected $appends = [
        'filled',
    ];


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
