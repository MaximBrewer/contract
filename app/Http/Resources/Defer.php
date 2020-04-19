<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Defer extends JsonResource
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
            'creditor' => [
                'id' => $this->creditor->id,
                'title' => $this->creditor->title,
            ],
            'supplier' => [
                'id' => $this->supplier->id,
                'title' => $this->supplier->title,
            ],
            'description' => $this->description
        ];
    }
}
