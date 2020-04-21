<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogisticLog extends Model
{
    protected $fillable = [
        'contragent_id',
        'logistic_id'
    ];
    
    public function logistic()
    {
        return $this->belongsTo('App\Logistic');
    }

    public function contragent()
    {
        return $this->belongsTo('App\Contragent');
    }
}
