<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Auction;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laracasts\Utilities\JavaScript\JavaScriptFacade as JavaScript;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {

        $user = JavaScript::put([
            'csrf_token' => csrf_token()
        ]);

        return view('personal.index', ['user' => $user]);
    }
}
