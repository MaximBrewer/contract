<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Interval extends Model
{
    protected $fillable = [
        'start_price',
        'volume',
        'auction_id',
        'from',
        'to'
    ];

    public function getFromAttribute($value)
    {
        return $value ? date(DATE_ATOM, strtotime($value)) : null;
    }


    public function getToAttribute($value)
    {
        return $value ? date(DATE_ATOM, strtotime($value)) : null;
    }

    public function getFfromAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.y');
    }


    public function getFtoAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.y');
    }

    public function bets()
    {
        return $this->hasMany('App\Bet')->orderBy('created_at', 'desc');
    }

    public function getLabelAttribute(){
        return Carbon::parse($this->from)->format('d.m.y') . ' - ' . Carbon::parse($this->to)->format('d.m.y');
    }


    public function getUndistributedVolumeAttribute()
    {
        $cnt = DB::select('select sum(volume) as busy_volume from bets where interval_id = ?', [$this->id]);
        return (float) $this->volume - (float) $cnt[0]->busy_volume;
    }


    public function getFreeVolumeAttribute()
    {
        $cnt = DB::select('select sum(volume) as busy_volume from bets where (approved_volume is not null and approved_volume > 1) and interval_id = ?', [$this->id]);
        return (float) $this->volume - (float) $cnt[0]->busy_volume;
    }

}
