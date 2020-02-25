<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\Phrase as PhraseResource;
use App\Phrase;

class Dialogue extends Model
{
    protected $fillable = [
        'contragent_1',
        'contragent_2'
    ];

    protected $appends = [
        'contragents'
    ];
    
    public function phrases()
    {
        return $this->hasMany(
            'App\Phrase'
        )->orderBy('created_at', 'desc')->limit(50);
    }
    
    public function getContragentsAttribute()
    {
        return [
            Contragent::find($this->contragent_1),
            Contragent::find($this->contragent_2),
        ];
    }
}
