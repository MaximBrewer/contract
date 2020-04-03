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
            'ballance' => $this->ballance,
            'auction_id' => $this->bet->auctiom->id,
        ];
    }
}
