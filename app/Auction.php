<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;
use App\Store;
use App\Message;
use App\Multiplicity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Contragent;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Message as MessageResource;
use App\Http\Resources\History as HistoryResource;


class Auction extends Model
{


    protected $fillable = [
        'picture',
        'contragent_id',
        'store_id',
        'comment',
        'start_at',
        'autosale',
        'finish_at',
        'product_id',
        'multiplicity_id',
        'step',
        'start_price',
        'volume',
        'prepay',
        'confirmed',
        'finished',
        'started',
        'can_bet',
        'mode'
    ];

    protected $appends = [
        'filled',
        'bidder',
        'bidders',
        'free_volume',
        'messages',
        'min_bet',
        'undistributed_volume'
    ];


    public function getUndistributedVolumeAttribute()
    {
        $cnt = DB::select('select sum(volume) as busy_volume from bets where auction_id = ?', [$this->id]);
        return (float) $this->volume - (float) $cnt[0]->busy_volume;
    }


    public function getFreeVolumeAttribute()
    {
        $cnt = DB::select('select sum(volume) as busy_volume from bets where (approved_volume is not null and approved_volume > 1) and auction_id = ?', [$this->id]);
        return (float) $this->volume - (float) $cnt[0]->busy_volume;
    }


    public function getMinBetAttribute()
    {
        if ($this->getUndistributedVolumeAttribute() > 0 || $this->volume == 0)
            return (float) $this->start_price;
        else {
            $cnt = DB::select('select min(price) as min from bets where approved_volume is null and auction_id = ?', [$this->id]);
            return (float) $cnt[0]->min;
        }
    }

    public function getStartAtAttribute($value)
    {
        return $value ? date(DATE_ATOM, strtotime($value)) : null;
    }


    public function getFinishAtAttribute($value)
    {
        return $value ? date(DATE_ATOM, strtotime($value)) : null;
    }

    public function getFilledAttribute()
    {
        $this->store;
        $this->bidder;
        $this->product;
        $this->multiplicity;
        $this->contragent;
        $this->tags;
        $this->bets;
        $this->images;
        $this->histories;
        return true;
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function store()
    {
        return $this->belongsTo('App\Store');
    }

    public function histories()
    {
        // return HistoryResource::collection(History::where('auction_id', $this->id)->orderBy('created_at', 'desc')->get());
        return $this->hasMany('App\History')->orderBy('created_at', 'desc');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    public function bets()
    {
        return $this->hasMany('App\Bet')->orderBy('created_at', 'desc');
    }

    public function getMessagesAttribute()
    {
        return MessageResource::collection(Message::where('auction_id', $this->id)->get());
    }

    public function multiplicity()
    {
        return $this->belongsTo('App\Multiplicity');
    }

    public function contragent()
    {
        return $this->belongsTo('App\Contragent');
    }

    public function setStepAttribute($value)
    {
        $this->attributes['step'] = str_replace(',', '.', $value);
    }

    public function setStartPriceAttribute($value)
    {
        $this->attributes['start_price'] = str_replace(',', '.', $value);
    }

    public function getBiddersAttribute()
    {

        $bidderIds = DB::select('select contragent_id, can_bet, observer from contragent_auction where auction_id = ?', [$this->id]);
        $bidderIdsArray = [];
        foreach ($bidderIds as $bidderId) $bidderIdsArray[] = $bidderId->contragent_id;
        $contragents = Contragent::whereIn('id', $bidderIdsArray)->select('id', 'title', 'fio', 'phone')->get();
        $cAgents = [];
        foreach ($contragents as $contragent) {
            $can_bet = 0;
            foreach ($bidderIds as $bidderId) {
                if ($bidderId->contragent_id == $contragent->id) {
                    $can_bet = $bidderId->can_bet;
                    $observer = $bidderId->observer;
                }
            }
            $cAgents[$contragent->id] = [
                'id' => $contragent->id,
                'title' => $contragent->title,
                'fio' => $contragent->fio,
                'phone' => $contragent->phone,
                'can_bet' => $can_bet,
                'observer' => $observer
            ];
        }
        return $cAgents;
    }

    public function getBidderAttribute()
    {
        if (Auth::user() && count(Auth::user()->contragents)) {
            foreach (Auth::user()->contragents[0]->auctions as $auction) {
                if ($auction->id == $this->id) return 1;
            }
        }
        return 0;
    }

    public function getImagesAttribute(){
        return Attachment::where('entity', 'auction')->where('entity_id', $this->id)->get();
    }
}
