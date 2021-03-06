<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Interval as IntervalResource;

class AuctionFull extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $bidder = 0;

        if (Auth::user()) {
            foreach (Auth::user()->contragents[0]->auctions as $auction) {
                if ($auction->id == $this->id) {
                    $bidder = 1;
                    break;
                }
            }
        }
        return [
            'id' => $this->id,
            'approved' =>$this->approved,
            'autosale' => $this->autosale,
            'bets' => $this->bets,
            'bidder' => $bidder,
            'bidders' => $this->bidders,
            'can_bet' => $this->can_bet,
            'comment' => $this->comment,
            'ncomment' => $this->ncomment,
            'delay_sell' => $this->delay_sell,
            'delay_buy' => $this->delay_buy,
            'confirmed' => $this->confirmed,
            'contragent' => $this->contragent,
            'step' => $this->step,
            'start_at' => $this->start_at,
            'finish_at' => $this->finish_at,
            'images' => $this->images,
            'mode' => $this->mode,
            'intervals' => IntervalResource::collection($this->intervals),
            'multiplicity' => [
                'id' => $this->multiplicity->id,
                'title' => $this->multiplicity->title
            ],
            'product' => [
                'id' => $this->product->id,
                'title' => $this->product->title
            ],
            'store' => [
                'id' => $this->store->id,
                'federal_district' => [
                    'id' => $this->store->federalDistrict->id,
                    'title' => $this->store->federalDistrict ? $this->store->federalDistrict->title : ''
                ],
                'region' => [
                    'id' => $this->store->region->id,
                    'title' => $this->store->region ? $this->store->region->title : ''
                ],
                'coords' => $this->store->coords,
                'address' => $this->store->address
            ],
            'started' => $this->started,
            'finished' => $this->finished,
            'histories' => $this->histories,
            'messages' => $this->messages,
            'prepay' => $this->prepay,
            'tags' => $this->tags,
        ];
    }
}


