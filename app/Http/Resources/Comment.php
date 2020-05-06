<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Comment extends JsonResource
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
            'by' => $this->by->title,
            'comment' => $this->comment,
            'contragent_id' => $this->contragent_id,
            'created_at' => $this->created_at,
            'id' => $this->id,
            'images' => $this->images,
            'to' => $this->to->title,
            'updated_at' => $this->updated_at,
            'votes' => $this->votes,
            'writer' => $this->writer,
        ];
    }
}
