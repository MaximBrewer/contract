<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Settlement extends JsonResource
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
            'id' => $this->id,
            'datetime' => $this->datetime,
            'balance' => $this->balance,
            'type' => $this->type,
            'auction_id' => !!$this->bet ? $this->bet->auction->id : '',
            'logistic' => $this->logistic ? [
                'title' => $this->logistic->title . ' ' . $this->logistic->gosznak
            ] : null
        ];
    }
}
