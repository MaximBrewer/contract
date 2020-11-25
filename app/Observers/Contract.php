<?php

namespace App\Observers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Contract as Model;
use App\ContractTemplate;
use App\Contragent;
use App\User;
use App\Notifications\Contract\Sign as SignNotification;
use App\Notifications\Contract\Unsign as UnsignNotification;
use App\Notifications\Contract\Request as RequestNotification;

class Contract
{
    //
    public function updating(Model $model)
    {
        if ($model->isDirty('status')) {
            $firsts = DB::table('user_contragent')->where('contragent_id', $model->contragent_id)->select('user_id')->get();
            $seconds = DB::table('user_contragent')->where('contragent_id', $model->contractTemplate->contragent_id)->select('user_id')->get();
            switch ($model->status) {
                case 0:
                case 2:
                    break;
                case 1:
                    foreach ($firsts as $userd) {
                        $user = User::find($userd->user_id);
                        if ($user) $user->notify(new RequestNotification($model));
                    }
                    foreach ($seconds as $userd) {
                        $user = User::find($userd->user_id);
                        if ($user) $user->notify(new RequestNotification($model));
                    }
                    break;
                case 3:
                    foreach ($firsts as $userd) {
                        $user = User::find($userd->user_id);
                        if ($user) $user->notify(new SignNotification($model));
                    }
                    foreach ($seconds as $userd) {
                        $user = User::find($userd->user_id);
                        if ($user) $user->notify(new SignNotification($model));
                    }
                    break;
                case 4:
                    foreach ($firsts as $userd) {
                        $user = User::find($userd->user_id);
                        if ($user)
                            $user->notify(new UnsignNotification(
                                $model,
                                $model->contractTemplate->contragent,
                                $model->contragent,
                            ));
                    }
                    foreach ($seconds as $userd) {
                        $user = User::find($userd->user_id);
                        if ($user)
                            $user->notify(new UnsignNotification(
                                $model,
                                $model->contractTemplate->contragent,
                                $model->contragent,
                            ));
                    }
                    $model->status = 0;
                    break;
                case 5:
                    foreach ($firsts as $userd) {
                        $user = User::find($userd->user_id);
                        if ($user)
                            $user->notify(new UnsignNotification(
                                $model,
                                $model->contragent,
                                $model->contractTemplate->contragent,
                            ));
                    }
                    foreach ($seconds as $userd) {
                        if ($user)
                            $user->notify(new UnsignNotification(
                                $model,
                                $model->contragent,
                                $model->contractTemplate->contragent,
                            ));
                    }
                    $model->status = 0;
                    break;
            }
        }
    }
}
