<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Multiplicity;
use App\Auction;
use App\Contragent;
use App\Store;


class Purchases extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $orbitsArr = [
            "purchases" => "совместные закупки",
            "delivery" => "совместная доставка",
            "granting" => "предоставление отсрочки",
            "warehouse" => "предоставление склада",
            "otherwise" => "иное"
        ];
        $orbits = json_decode($this->orbits);
        
        $orbits = $orbits ? array_map(function ($i) use ($orbitsArr) {
            return $orbitsArr[$i];
        }, $orbits) : [];

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
            'lot' => $auction->product ? [
                'id' => $auction->product->id,
                'title' => $auction->product->title
            ] : null,
            'store' => $store ? [
                'id' => $store->id,
                'address' => $store->address
            ] : null,
            'contragent' => $contragent ? [
                'id' => $contragent->id,
                'title' => $contragent->title
            ] : null,
            'auction' => [
                'id' => $auction->id,
                'finish_at' => $auction->finish_at,
                'mode' => $auction->mode,
                'multiplicity' => [
                    'title' => $auction->multiplicity->title,
                    'coefficient' => $auction->multiplicity->coefficient
                ]
            ],
            'sum' => $sum,
            'defer' => [
                'description' => $this->description,
                'orbits' => $orbits
            ],
            'rest' => $rest,
            'approved_contract' => $this->approved_contract,
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
