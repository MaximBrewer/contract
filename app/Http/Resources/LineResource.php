<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LineResource extends JsonResource
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
            'contragent' => [
                'id' => $this->contragent->id,
                'title' => $this->contragent->title,
            ],
            'author' => $this->contragent->title,
            // 'user' => [
            //     'id' => $this->user->id,
            //     'name' => $this->user->name,
            // ],
            'message' => $this->message,
            'dispute_id' => $this->dispute_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
