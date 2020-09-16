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
use App\Bet;
use App\Contragent;
use App\Interval;
use App\Kind;
use App\Settlement;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade as PDF;

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
        $user = User::find(Auth::user()->id);
        if (!$request->session()->get('_api_token')) {
            $token = Str::random(80);

            $user->forceFill([
                'api_token' => hash('sha256', $token),
            ])->save();

            $request->session()->put('_api_token', $token);
        }
        if ($request->kind) {
            $user->update([
                'kind_id' => $request->kind
            ]);
            return redirect('/personal');
        }
        return view('personal.index', ['kinds' => Kind::all()]);
    }

    public function invoice(Request $request)
    {
        $settlement = Settlement::findOrFail($request->id);
        $pdf = PDF::loadView('pdf.invoice', ['settlement' => $settlement]);
        return $pdf->stream();


        response()
            ->view('pdf.invoice', ['settlement' => $settlement], 200);
    }

    public function invoiceNew(Request $request, $id)
    {

        $bet = Bet::findOrfail($id);
        $auction = Auction::findOrfail($bet->auction_id);
        $interval = Interval::findOrfail($bet->interval_id);

        $settlement = Settlement::create([
            'contragent_id' => Auth::user()->contragents[0]->id,
            'balance' => round($bet->correct * $bet->volume * $auction->prepay) / 100,
            'method' => 'invoice',
            'type' => 'credit',
            'status' => 'processing'
        ]);

        $recipient = Contragent::findOrfail($auction->contragent_id);

        $pdf = PDF::loadView('pdf.invoiceNew', ['settlement' => $settlement, 'interval' => $interval, 'auction' => $auction, 'bet' => $bet, 'recipient' => $recipient]);
        return $pdf->stream();


        response()
            ->view('pdf.invoiceNew', ['settlement' => $settlement], 200);
    }
}
