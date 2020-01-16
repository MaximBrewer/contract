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
    public function index(Request $r)
    {
        if((int)$r->get('federal_district_id')) return Region::where('federal_district_id', (int)$r->federal_district_id)->get();
        return Region::all();
    }
}
