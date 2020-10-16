<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Contract extends Model
{
    protected $fillable = [
        'acceptor_header',
        'contract_template_id',
        'contragent_id',
        'status'
    ];
}
