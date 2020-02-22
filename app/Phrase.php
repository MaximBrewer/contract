<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phrase extends Model
{
    //
    protected $fillable = [
        'dialogue_contragent_id',
        'text',
        'sent'
    ];

}
