<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Result as ResultResource;
use App\Http\Resources\Purchases as PurchasesResource;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Bet;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\LogisticLog as LogisticLogResource;
use App\LogisticLog;
use Illuminate\Database\Eloquent\Builder;

class ResultsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ['results' => ResultResource::collection(
            DB::table('bets')
                ->leftJoin('auctions', 'bets.auction_id', '=', 'auctions.id')
                ->select('bets.*')
                ->where('auctions.contragent_id', Auth::user()->contragents[0]->id)
                ->whereNotNull(['approved_contract'])
                ->orderBy('id', 'DESC')
                ->get()
        ), 'logistic_logs' => LogisticLogResource::collection(
            LogisticLog::whereHas('logistic', function (Builder $query) {
                $query->where('contragent_id', Auth::user()->contragents[0]->id);
            })->get()
        )];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function purchases()
    {
        return ['purchases' => PurchasesResource::collection(
            DB::table('bets')
                ->leftJoin('defers', function ($join) {
                    $join->on('bets.contragent_id', '=', 'defers.creditor_id')->on('bets.distributor_id', '=', 'defers.supplier_id');
                })
                ->select(['bets.*', 'defers.description', 'defers.orbits', 'defers.creditor_id'])
                ->where('bets.distributor_id', Auth::user()->contragents[0]->id)
                ->whereNotNull(['approved_contract'])
                ->orderBy('id', 'DESC')
                ->get()
        )];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r)
    {
        $bet = Bet::find($r->id);
        if ($bet->auction->contragent_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }
        $bet->update([
            'message' => $r->message
        ]);
        return $bet;
    }
}
