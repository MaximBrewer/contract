<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Contragent;
use App\DialogueContragent;
use Carbon\Carbon;
use App\Http\Resources\Contragent as ContragentResource;

class Phrase extends JsonResource
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
            'contragent' => new ContragentResource(Contragent::find(DialogueContragent::find($this->dialogue_contragent_id)->contragent_id)),
            'text' => $this->text,
            'sent' => $this->sent,
            'dialogue_contragent_id' => $this->dialogue_contragent_id,
            'created_at' => Carbon::parse($this->created_at)->format('d.m.Y в H:i:s'),
            'updated_at' => $this->updated_at,
        ];
    }
}
