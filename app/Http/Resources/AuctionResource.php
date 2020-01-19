<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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

        return [
            // 'id' => $this->id,
            // 'volume' => $this->volume,
            // 'start_price' => $this->start_price,
            // 'step' => $this->step,
            // 'comment' => $this->comment,
            // 'contragent' => [
            //     'id' => $this->contragent->id,
            //     'title' => Bidders::collection($this->posts),
            // ],
            // 'start_at' => $this->start_at,
            // 'finish_at' => $this->finish_at,
            // 'bidders' => 
        ];
    }
}
