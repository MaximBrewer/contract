<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Proposal extends Model
{
    //
    protected $fillable = [
        'dispute_id',
        'contragent_id',
        'user_id',
        'message'
    ];

    public function contragent()
    {
        return $this->belongsTo('App\Contragent');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function dispute()
    {
        return $this->belongsTo('App\Dispute');
    }

    public function getVoteAttribute()
    {
        $vote = Vote::where('contragent_id', Auth::user()->contragents[0]->id)->where('proposal_id', $this->id)->first();
        return $vote;
    }

    public function votes()
    {
        return $this->hasMany('App\Vote');
    }
}
