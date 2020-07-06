<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LineResource;
use App\Http\Resources\ProposalResource;

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
            'votes' => $this->votes,
            'proposal' => new ProposalResource($this->proposal),
            'proposals' => ProposalResource::collection($this->proposals),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
