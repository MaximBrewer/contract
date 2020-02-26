<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\Contragent as ContragentResource;

class History extends Model
{
    protected $table = 'histories';

    protected $fillable = [
        'price',
        'volume',
        'auction_id',
        'contragent_id'
    ];

    protected $appends = [
        'contragent'
    ];

    public function getContragentAttribute()
    {
        return new ContragentResource(Contragent::find($this->contragent_id));
    }
}
