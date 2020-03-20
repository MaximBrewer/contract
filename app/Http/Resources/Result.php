<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Result extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $volume = $this->auction->multiplicity->coefficient * $this->volume;
        $sum = $this->correct * $volume;
        $rest = ($this->correct - $this->auction->start_price) * $volume;
        $tooltip = "0.05% * " . $sum . " + " . $rest . " * 5%";
        $reward = 0.0005 * $sum + $rest * 0.05;

        return [
            'id' => $this->id,
            'contragent' => $this->contragent,
            'auction' => [
                'id' => $this->auction->id,
                'finish_at' => $this->auction->finish_at,
                'multiplicity' => [
                    'title' => $this->auction->multiplicity->title,
                    'coefficient' => $this->auction->multiplicity->coefficient
                ]
            ],
            'bid' => $this->correct,
            'volume' => $this->volume,
            'message' => $this->message,
            'reward' => [
                'sum' => $reward,
                'tooltip' => $tooltip,
            ]
        ];
    }
}