<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Contragent extends Model
{
    protected $fillable = [
        'title',
        'fio',
        'inn',
        'federal_district_id',
        'region_id',
        'phone',
        'legal_address',
        'kpp',
        'bank',
        'nds',
        'bik',
        'rs',
        'requisites'
    ];
    protected $hidden = [
        'federal_district_id',
        'region_id'
    ];

    protected $appends = [
        'filled',
        'balance'
    ];


    public function contractTemplates()
    {
        return $this->hasMany('App\ContractTemplate');
    }

    public function getFilledAttribute()
    {
        $this->stores;
        $this->types;
        $this->federalDistrict;
        $this->region;
        $this->distributors;
        // $this->auctions;
        return true;
    }

    public function getBalanceAttribute()
    {
        $cnt0 = DB::select("SELECT sum(balance) as s FROM settlements where `contragent_id`=? and type = 'credit' and status='done'", [$this->id]);
        $cnt1 = DB::select("SELECT sum(balance) as s FROM settlements where `contragent_id`=? and type = 'debit' and status='done'", [$this->id]);
        return (float) ($cnt0[0]->s - $cnt1[0]->s);
    }


    public function distributors()
    {
        return $this->hasManyThrough('App\Contragent', 'App\Defer', 'supplier_id', 'id', 'id', 'creditor_id')->select(['contragents.title', 'contragents.id', 'defers.description'])->where('status', 'both');
    }

    public function region()
    {
        return $this->belongsTo('App\Region');
    }

    public function federalDistrict()
    {
        return $this->belongsTo('App\FederalDistrict');
    }

    public function types()
    {
        return $this->belongsToMany('App\Type', 'contragent_type');
    }

    public function auctions()
    {
        return $this->belongsToMany('App\Auction', 'contragent_auction')->orderBy('id', 'desc');
    }

    public function bid_auctions()
    {
        return $this->belongsToMany('App\Auction', 'contragent_auction')->where('finished', '<>', 1)->orderBy('id', 'desc');
    }

    public function archive()
    {
        return $this->belongsToMany('App\Auction', 'contragent_auction')->where('finished', 1)->orderBy('id', 'desc');
    }

    public function stores()
    {
        return $this->hasMany('App\Store');
    }
    public function bets()
    {
        return $this->hasMany('App\Bet');
    }
}
