<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\Contragent as ContragentResource;

class History extends Model
{
    protected $table = 'histories';

    protected $fillable = [
        'price',
        'interval_id',
        'volume',
        'auction_id',
        'contragent_id',
        'distributor_id'
    ];

    protected $appends = [
        'contragent',
        'distributor'
    ];

    public function getContragentAttribute()
    {
        return new ContragentResource(Contragent::find($this->contragent_id));
    }

    public function getDistributorAttribute()
    {
        $distributor = Contragent::find($this->distributor_id);
        return $distributor ? $distributor->title : "";
    }
}
