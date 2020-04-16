<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Defer extends Model
{
    protected $fillable = [
        'creditor_id',
        'supplier_id',
        'description'
    ];

    public function supplier()
    {
        return $this->belongsTo('App\Contragent', "supplier_id");
    }

    public function creditor()
    {
        return $this->belongsTo('App\Contragent', "creditor_id");
    }
    
}
