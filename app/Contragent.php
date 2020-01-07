<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contragent extends Model
{
    protected $fillable = [
        'title',
        'inn',
        'federal_district_id',
        'region_id',
        'phone',
        'legal_address'
    ];
    protected $hidden = [
        'federal_district_id',
        'region_id'
    ];

    protected $appends = [
        'filled',
    ];

    public function getFilledAttribute()
    {
        $this->stores;
        $this->types;
        $this->federalDistrict;
        $this->region;
        return true;
    }
    
    public function region()
    {
        return $this->belongsTo('App\Region');
    }
    
    public function federalDistrict()
    {
        return $this->belongsTo('App\FederalDistrict');
    }
    
    public function types()
    {
        return $this->belongsToMany('App\Type', 'contragent_type');
    }

    public function stores()
    {
        return $this->hasMany('App\Store');
    }

}
