<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Region extends Model
{
    
    public function federalDistrict()
    {
        return $this->belongsTo('App\FederalDistrict');
    }
}
