<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    //
    protected $fillable = [
        'dispute_id',
        'contragent_id',
        'proposal_id'
    ];

    public function proposal()
    {
        return $this->belongsTo('App\Proposal');
    }

    public function contragent()
    {
        return $this->belongsTo('App\Contragent');
    }

    public function dispute()
    {
        return $this->belongsTo('App\Dispute');
    }
}
