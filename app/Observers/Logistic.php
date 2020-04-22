<?php

namespace App\Observers;

use App\Logistic as Model;
use App\Settlement;

class Logistic
{
    public function updated(Model $model)
    {
        if ($model->isDirty('coords') || $model->isDirty('available_from')) {
            Settlement::create([
                'contragent_id' => $model->contragent_id,
                'logistic_id' => $model->id,
                'balance' => 10,
                'type' => 'debit',
                'status' => 'done',
            ]);
        }
    }
}
