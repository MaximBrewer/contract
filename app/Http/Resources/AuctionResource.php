<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AuctionResource extends JsonResource
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
            'volume' => $this->volume,
            'free_volume' => $this->free_volume,
            'start_price' => $this->start_price,
            'min_bet' => $this->min_bet,
            'step' => $this->step,
            'comment' => $this->comment,
            'start_at' => $this->start_at,
            'finish_at' => $this->finish_at,
            'confirmed' => $this->confirmed,
            'contragent' => [
                'id' => $this->contragent->id,
                'title' => $this->contragent->title,
                'rating' => $this->contragent->rating,
            ],
            'multiplicity' => [
                'id' => $this->multiplicity->id,
                'title' => $this->multiplicity->title
            ],
            'product' => [
                'id' => $this->product->id,
                'title' => $this->product->title
            ],
            'store' => [
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
            'bidder' => $bidder,
            'tags' => $this->tags
        ];
    }
}
