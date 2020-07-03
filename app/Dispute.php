<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Dispute extends Model
{
    //
    protected $fillable = [
        'status'
    ];

    public function getProposalAttribute()
    {
        $proposal = Proposal::where('contragent_id', Auth::user()->contragents[0]->id)->where('dispute_id', $this->id)->first();
        return $proposal ? $proposal : new Proposal();
    }

    public function contragents()
    {
        return $this->belongsToMany('App\Contragent', 'dispute_contragent');
    }

    public function getFirstAttribute()
    {
        return Comment::where('writer', $this->contragents[0]->id)->where('contragent_id', $this->contragents[1]->id)->first();
    }

    public function getSecondAttribute()
    {
        return Comment::where('writer', $this->contragents[1]->id)->where('contragent_id', $this->contragents[0]->id)->first();
    }

    public function lines()
    {
        return $this->hasMany('App\Line');
    }
}
