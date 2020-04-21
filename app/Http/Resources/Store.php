<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Store extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $title = $this->contragent ? $this->contragent->title . ' ' : '';
        return [
            'id' => $this->id,
            'title' => $title . $this->address,
            'coords' => $this->coords,
        ];
    }
}
