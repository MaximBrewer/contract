<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Contragent;
use App\Http\Resources\Contragent as ContragentResource;

class Dialogue extends JsonResource
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
            'contragent' => new ContragentResource(
                Contragent::find($this->contragents->whereNotIn('contragent_id', [User::find(Auth::user()->id)->contragents[0]->id])->first()->contragent_id)
            ),
            'company' => new ContragentResource(
                Contragent::find($this->contragents->whereIn('contragent_id', [User::find(Auth::user()->id)->contragents[0]->id])->first()->contragent_id)
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
