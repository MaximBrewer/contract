<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Defer;
use App\Http\Resources\Defer as DeferResource;
use App\User;
use Illuminate\Support\Facades\Auth;

class DefersController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DeferResource::collection(Defer::all());
    }

    public function store(Request $r){

        Defer::create([
            'creditor_id' => User::find(Auth::user()->id)->contragents[0]->id,
            'supplier_id' => $r->post('supplier_id'),
            'description' => $r->post('description'),
        ]);
        return DeferResource::collection(Defer::all());

    }

    public function update(Request $r, $id){

        $defer = Defer::findOrFail($id);

        if ($defer->creditor_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        $defer->update([
            'supplier_id' => $r->post('supplier_id'),
            'description' => $r->post('description'),
        ]);

        return DeferResource::collection(Defer::all());
    }

    public function destroy(Request $r, $id){

        $defer = Defer::findOrFail($id);

        if ($defer->creditor_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        Defer::findOrFail($id)->delete();
        return DeferResource::collection(Defer::all());
    }

}
