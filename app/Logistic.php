<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logistic extends Model
{
    //
    protected $fillable = [
        'title',
        'gosznak',
        'coords',
        'address',
        'description',
        'contragent_id',
        'purpose_id',
        'capacity_id',
        'federal_district_id',
        'available_from',
        'region_id',
        'description'
    ];
    
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
    
    public function capacity()
    {
        return $this->belongsTo('App\Capacity');
    }
    
    public function purpose()
    {
        return $this->belongsTo('App\Purpose');
    }
}
