<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\Phrase as PhraseResource;
use App\Phrase;
use App\DialogueContragent;

class Dialogue extends Model
{
    protected $appends = [
        'phrases'
    ];
    
    public function getPhrasesAttribute()
    {
        return PhraseResource::collection(Phrase::whereIn('dialogue_contragent_id', function ($query) {
            $query->select('id')
                ->from(with(new DialogueContragent())->getTable())
                ->where('dialogue_id', $this->id);
        })->orderBy('created_at', 'desc')->get());
    }

    public function contragents()
    {
        return $this->hasMany(
            'App\DialogueContragent'
        );
    }
}
