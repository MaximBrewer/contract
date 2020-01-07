<?php

namespace App\Http\Controllers\Api\V1;

use App\Multiplicity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MultiplicitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Multiplicity::all();
    }
}
