<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Joint extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $orbitsArr = [
            "purchases" => "совместные закупки",
            "delivery" => "совместная доставка",
            "granting" => "предоставление отсрочки",
            "warehouse" => "предоставление склада",
            "otherwise" => "иное"
        ];
        $orbits = json_decode($this->orbits);
        $orbits = $orbits ? array_map(function ($i) use ($orbitsArr) {
            return $orbitsArr[$i];
        }, $orbits) : [];

        return [
            'id' => $this->id,
            'creditor' => [
                'id' => $this->creditor->id,
                'title' => $this->creditor->title,
            ],
            'supplier' => [
                'id' => $this->supplier->id,
                'title' => $this->supplier->title,
            ],
            'status' => __("status." . $this->status),
            'description' => $this->description,
            'orbits' => $orbits
        ];
    }
}
