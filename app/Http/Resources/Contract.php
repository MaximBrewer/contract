<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Contract extends JsonResource
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
            'acceptor_header' => $this->acceptor_header,
            'contract_template_id' => $this->contract_template_id,
            'contragent_id' => $this->contragent_id,
            'recipient' => $this->contractTemplate->contragent->title,
            'statusText' => parent::getStatus($this->status),
            'status' => $this->status,
            'date' => $this->created_at,
            'version' => $this->contractTemplate->version
        ];
    }
}
