<?php

namespace App\Http\Controllers\Api\V1;

use App\Type;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Type::all();
    }
}
