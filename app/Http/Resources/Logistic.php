<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Logistic extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $purposes = [];
        foreach ($this->purposes as $purpose) {
            $purposes[] = [
                'id' => $purpose->id,
                'title' => $purpose->title,
            ];
        }
        return [
            'id' => $this->id,
            'contragent' => [
                'id' => $this->contragent->id,
                'title' => $this->contragent->title,
                'phone' => $this->contragent->phone,
            ],
            'federal_district' => [
                'id' => $this->federalDistrict->id,
                'title' => $this->federalDistrict->title,
            ],
            'region' => [
                'id' => $this->region->id,
                'title' => $this->region->title,
            ],
            'purposes' => $purposes,
            'capacity' => [
                'id' => $this->capacity->id,
                'title' => $this->capacity->title,
            ],
            'title' => $this->title,
            'gosznak' => $this->gosznak,
            'description' => $this->description,
            'available_from' => date("Y-m-d", strtotime($this->available_from)),
            'coords' => $this->coords,
            'address' => $this->address,
        ];
    }
}
