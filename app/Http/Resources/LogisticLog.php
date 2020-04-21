<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class LogisticLog extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $carbon = new Carbon($this->created_at);
        return [
            'id' => $this->id,
            'vehicle' => $this->logistic->title . ' ' . $this->logistic->gosznak,
            'contragent' => [
                'id' => $this->contragent->id,
                'title' => $this->contragent->title
            ],
            'datetime' => $carbon->diffForHumans()
        ];
    }
}
