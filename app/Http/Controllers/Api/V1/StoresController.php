<?php

namespace App\Http\Controllers\Api\V1;

use App\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Store::all();
    }
}
