<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Contragent;

class History extends JsonResource
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
            'contragent' => Contragent::find($this->contragent_id)->first()->title,
            'price' => $this->price,
            'volume' => $this->volume,
            'created_at' => date("d.m.Y H:i", strtotime($this->created_at))
        ];
    }
}
