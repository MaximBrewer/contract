<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LineResource;

class DisputeResource extends JsonResource
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
            'lines' => LineResource::collection($this->lines),
            'contragents' => ContragentResource::collection($this->contragents),
            'first_review' => $this->first,
            'second_review' => $this->second,
            'proposal' => $this->proposal,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
