<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contragent extends Model
{
    
    public function setFederalDistrictIdAttribute($value)
    {
        $fd_id = Region::find($this->attributes['region_id'])->federal_district_id;
        $this->attributes['federal_district_id'] = $fd_id;
    }

}
