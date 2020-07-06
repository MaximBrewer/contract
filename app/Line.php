<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    //
    protected $fillable = [
        'dispute_id',
        'contragent_id',
        'user_id',
        'message'
    ];

    public function contragent()
    {
        return $this->belongsTo('App\Contragent');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function dispute()
    {
        return $this->belongsTo('App\Dispute');
    }
}
