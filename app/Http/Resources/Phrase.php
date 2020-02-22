<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Contragent;
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
            'contragent' => new ContragentResource(Contragent::find($this->contragent_id)),
            'text' => $this->text,
            'shown' => $this->shown,
            'created_at' => Carbon::parse($this->created_at)->format('d.m.Y в H:i:s'),
            'updated_at' => $this->updated_at,
        ];
    }
}
