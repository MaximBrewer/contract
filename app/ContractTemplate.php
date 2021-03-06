<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ContractTemplate extends Model
{
    
    protected $fillable = [
        'contract_type_id',
        'proposer_header',
        'contragent_id',
        'text',
        'title',
        'accepting',
        'common_title',
        'recipient_title',
        'receiver_title',
        'version'
    ];

    public function contragent()
    {
        return $this->belongsTo('App\Contragent');
    }
}
