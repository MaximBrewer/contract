<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{

    protected $appends = [
        'contragent',
    ];

    public function getContragentAttribute(){

        $contragent = \App\Contragent::findOrFail($this->contragent_id);
        return [
            'id' => $contragent->id,
            'title' => $contragent->title,
        ];
    }

}
