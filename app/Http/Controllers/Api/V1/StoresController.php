<?php

namespace App\Http\Controllers\Api\V1;

use App\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(
            !Auth::user()
        )
        return Store::all();

        return Store::where('contragent_id', Auth::user()->contragents[0]->id)->get();

    }
}
