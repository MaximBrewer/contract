<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\PhraseCreated;

class Phrase extends Model
{

    protected $fillable = [
        'dialogue_id',
        'contragent_id',
        'text',
        'shown'
    ];

}
