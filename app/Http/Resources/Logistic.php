<?php

namespace App\Http\Resources;

use App\LogisticLog;
use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use Illuminate\Support\Facades\Auth;


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
        $logLog = LogisticLog::where('contragent_id', User::find(Auth::user()->id)->contragents[0]->id)->where('logistic_id', $this->id)->first();
        return [
            'id' => $this->id,
            'contragent' => [
                'id' => $this->contragent->id,
                'title' => $this->contragent->title,
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
            'phone' => $logLog ? $this->contragent->phone : null,
            'title' => $this->title,
            'gosznak' => $this->gosznak,
            'description' => $this->description,
            'available_from' => date("Y-m-d", strtotime($this->available_from)),
            'coords' => $this->coords,
            'address' => $this->address,
        ];
    }
}
