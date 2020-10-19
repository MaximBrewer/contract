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
use App\ContractType;
use App\Contragent;
use App\Interval;
use App\ContractTemplate;
use App\Kind;
use App\Contract;
use App\Settlement;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewInvoiceReceiver;
use App\Mail\NewInvoiceRecipient;
use Illuminate\Support\Facades\DB;

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

        $templates = DB::table('contract_templates AS a')->select(DB::raw('a.*'))
            ->leftJoin('contract_templates AS b', function ($join) {
                $join->on('a.contract_type_id', '=', 'b.contract_type_id')
                    ->on('a.version', '<', 'b.version');
            })->where(
                'a.contragent_id',
                Auth::user()->contragents[0]->id
            )->whereNull(
                'b.version'
            )
            ->get();

        JavaScript::put([
            'contractTypes' => ContractType::all(),
            'contractTypesMine' => $templates
        ]);

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
        $recipient = Contragent::findOrfail($auction->contragent_id);
        $receiver = Contragent::findOrfail($bet->contragent_id);
        $settlement = Settlement::where('bet_id', $bet->id)->first();
        if (!$settlement) {
            $settlement = Settlement::create([
                'bet_id' => $bet->id,
                'contragent_id' => $receiver->id,
                'balance' => round($bet->correct * $bet->volume * (float)$auction->multiplicity->coefficient * $auction->prepay) / 100,
                'method' => 'invoice',
                'type' => 'credit',
                'status' => 'processing'
            ]);
            $users = DB::table('users')
                ->whereRaw('id in (select user_id from user_contragent where contragent_id=?)', [$recipient->id])
                ->get();
            foreach ($users as $user) {
                Mail::to($user->email)->send(new NewInvoiceRecipient($settlement, $receiver, $recipient));
            }
            $users = DB::table('users')
                ->whereRaw('id in (select user_id from user_contragent where contragent_id=?)', [$receiver])
                ->get();
            foreach ($users as $user) {
                Mail::to($user->email)->send(new NewInvoiceReceiver($settlement, $receiver, $recipient));
            }
        }

        $pdf = PDF::loadView('pdf.invoiceNew', ['settlement' => $settlement, 'interval' => $interval, 'auction' => $auction, 'bet' => $bet, 'recipient' => $recipient]);

        return $pdf->stream();
        response()->view('pdf.invoiceNew', ['settlement' => $settlement], 200);
    }

    public function contract(Request $request, $id)
    {
        $contract = Contract::findOrfail($id);
        $recivier = Contragent::findOrfail($contract->contragent_id);
        $recipient = Contragent::findOrfail($contract->contractTemplate->contragent_id);

        $pdf = PDF::loadView('pdf.contract', compact(['contract','recivier','recipient']));

        return $pdf->stream();
        return response()->view('pdf.contract', compact(['contract','recivier','recipient']));
    }
}
