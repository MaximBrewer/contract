<?php

namespace App\Http\Controllers\Api\V1;

use App\Region;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Region::all();
    }
}
