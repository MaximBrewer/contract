<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;

class ContragentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        return view('personal.index');
    }
}
