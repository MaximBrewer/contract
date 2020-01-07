<?php

namespace App\Http\Controllers\Api\V1;

use App\FederalDistrict;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FederalDistrictsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FederalDistrict::all();
    }
}
