<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Contragent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::user()) return redirect('/login');
        if (Auth::user()->role_id == 1 || Auth::user()->role_id == 3) return $next($request);

        if (empty(Auth::user()->contragents->toArray())){

            $contragent = \App\Contragent::where('inn', Auth::user()->inn);

            if(!$contragent){
                $contragent = \App\Contragent::create([
                    "inn" => Auth::user()->inn
                ]);
            }
            var_dump(Auth::user()->inn);
            die;

            Auth::user()->contragents()->sync([$contragent->id]);

            if(
                !$contragent->title
                || !$contragent->federal_district_id
                || !$contragent->region
                || !$contragent->fio
                || !$contragent->phone
            ) return redirect('/personal#/contragents/edit/' . $contragent->id);

        }

        return $next($request);
    }
}
