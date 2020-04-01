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
        'legal_address'
    ];
    protected $hidden = [
        'federal_district_id',
        'region_id'
    ];

    protected $appends = [
        'filled',
        'balance'
    ];

    public function getFilledAttribute()
    {
        $this->stores;
        $this->types;
        $this->federalDistrict;
        $this->region;
        // $this->auctions;
        return true;
    }

    public function getBalanceAttribute()
    {
        $cnt = DB::select("select (SELECT sum(balance) FROM settlements where `contragent_id`=? and type = 'debit' and status='done') - (SELECT sum(balance) FROM settlements where `contragent_id`=? and type = 'credit' and status='done') as itog", [$this->id, $this->id]);
        return (int) $cnt[0]->itog;
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
    public function bets(){
        return $this->hasMany('App\Bet');
    }

}
