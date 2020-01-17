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
        //\DB::connection()->enableQueryLog();
        $carbon = new Carbon();
        //$queries = \DB::getQueryLog();
        //info($queries);

        \DB::connection()->enableQueryLog();
        \DB::select('select id from auctions where time(start_at) between time(?) and time(?)',
            [
                $carbon->subMinute()->toDateTimeString(),
                $carbon->addMinute()->toDateTimeString()
            ]
        );
        $queries = \DB::getQueryLog();
        info($queries);

        $token = Str::random(80);

        User::find(Auth::user()->id)->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        $user = JavaScript::put([
            'user' => Auth::user(),
            'csrf_token' => csrf_token(),
            'api_token' => $token
        ]);

        return view('personal.index', ['user' => $user]);
    }
}
