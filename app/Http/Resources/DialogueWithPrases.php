<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Http\Resources\Phrase as PhraseResource;

class DialogueWithPrases extends JsonResource
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
            'count' => $this->count,
            'contragents' => $this->contragents,
            'phrases' => PhraseResource::collection($this->phrases),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
