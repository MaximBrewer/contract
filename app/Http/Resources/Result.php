<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Multiplicity;
use App\Auction;
use App\Contragent;
use App\Store;

class Result extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $auction = Auction::find($this->auction_id);
        $store = Store::find($this->store_id);
        $contragent = Contragent::find($this->contragent_id);
        $volume = $auction->multiplicity->coefficient * $this->volume;
        $sum = $this->correct * $volume;
        $rest = ($this->correct - $auction->start_price) * $volume;
        $tooltip = "0.05% * " . $sum . " + " . $rest . " * 5%";
        $reward = 0.0005 * $sum + $rest * 0.05;

        return [
            'id' => $this->id,
            'store' => $store ? [
                'id' => $store->id,
                'address' => $store->address
            ] : null,
            'contragent' => [
                'id' => $contragent->id,
                'title' => $contragent->title
            ],
            'auction' => [
                'id' => $auction->id,
                'finish_at' => $auction->finish_at,
                'multiplicity' => [
                    'title' => $auction->multiplicity->title,
                    'coefficient' => $auction->multiplicity->coefficient
                ]
            ],
            'sum' => $sum,
            'rest' => $rest,
            'bid' => $this->correct,
            'volume' => $this->volume,
            'message' => $this->message,
            'reward' => [
                'sum' => $reward,
                'tooltip' => $tooltip,
            ]
        ];
    }
}