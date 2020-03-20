<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Result as ResultResource;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Bet;

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
            Bet::where([
                'contragent_id' => User::find(Auth::user()->id)->contragents[0]->id
            ])
                ->whereNotNull('approved_contract')
                ->orderBy('created_at', 'desc')->get()
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
        if ($bet->contragent_id != Auth::user()->contragents[0]->id) {
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
