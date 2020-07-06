<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProposalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->id)
            return [
                'id' => $this->id,
                'contragent' => [
                    'id' => $this->contragent->id,
                    'title' => $this->contragent->title,
                ],
                // 'user' => [
                //     'id' => $this->user->id,
                //     'name' => $this->user->name,
                // ],
                'message' => $this->message,
                'votes' => $this->votes,
                'vote' => $this->vote,
                'dispute_id' => $this->dispute_id,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ];
        else return ['message' => ''];
    }
}
