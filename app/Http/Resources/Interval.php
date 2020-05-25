<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Interval extends JsonResource
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
            'from' => $this->from,
            'bets' => $this->bets,
            'to' => $this->to,
            'start_price' => $this->start_price,
            'volume' => $this->volume,
            'label' => $this->label,
            'undistributed_volume' => $this->undistributed_volume,
            'free_volume' => $this->free_volume,
        ];
    }
}
