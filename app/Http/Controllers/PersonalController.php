<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('personal.index');
    }
}
