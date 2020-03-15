<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Auction;
use App\User;
use App\Dialogue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laracasts\Utilities\JavaScript\JavaScriptFacade as JavaScript;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Kind;
use Illuminate\Support\Facades\View;

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

    public function index(Request $request)
    {
        if (!$request->session()->get('_api_token')) {
            $token = Str::random(80);

            User::find(Auth::user()->id)->forceFill([
                'api_token' => hash('sha256', $token),
            ])->save();

            $request->session()->put('_api_token', $token);
        }
        return view('personal.index', ['kinds' => Kind::all()]);
    }
}
