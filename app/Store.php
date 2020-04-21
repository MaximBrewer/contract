<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Store extends Model
{
    protected $fillable = [
        'coords',
        'address',
        'contragent_id',
        'federal_district_id',
        'region_id'
    ];

    protected $appends = [
        'filled',
    ];

    public function getFilledAttribute()
    {
        $this->federalDistrict;
        $this->region;
        return true;
    }
    
    public function federalDistrict()
    {
        return $this->belongsTo('App\FederalDistrict');
    }
    
    public function region()
    {
        return $this->belongsTo('App\Region');
    }
    
    public function contragent()
    {
        return $this->belongsTo('App\Contragent');
    }
}
