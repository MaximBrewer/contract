<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Result as ResultResource;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Bet;
use Illuminate\Support\Facades\DB;

class ResultsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ResultResource::collection(
            DB::table('bets')
            ->leftJoin('auctions', 'bets.auction_id', '=', 'auctions.id')
            ->select('bets.*')
            ->where('auctions.contragent_id', Auth::user()->contragents[0]->id)
            ->whereNotNull(['approved_contract'])
            ->orderBy('id', 'DESC')
            ->get()
        );
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
