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
        $k = $this->contragent_1 == User::find(Auth::user()->id)->contragents[0]->id;
        $contragent = $k ? Contragent::find($this->contragent_2) : Contragent::find($this->contragent_1);
        $company = $k ? Contragent::find($this->contragent_1) : Contragent::find($this->contragent_2);
        return [
            'id' => $this->id,
            'count' => $this->count,
            'company' => new ContragentResource($company),
            'contragent' => new ContragentResource($contragent),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
