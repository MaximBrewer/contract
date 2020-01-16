<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Target;
use Illuminate\Validation\Rule;
use \App\Contragent;
use Illuminate\Support\Facades\Auth;
use \App\Store;
use Illuminate\Support\Facades\Validator;

class TargetsController extends Controller
{



    public function all(Request $request, $action = null)
    {

        return Target::all();
    }


    public function index(Request $request, $action = null)
    {

        return Target::where('contragent_id', Auth::user()->contragents[0]->id)->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            "multiplicity.id" => "required|exists:multiplicities,id",
            "product.id" => "required|exists:products,id",
            "store.id" => "required|exists:stores,id",
            "volume" => "required|numeric|min:1",
        ]);

        $target = Target::create([
            'contragent_id' => Auth::user()->contragents[0]->id,
            'product_id' => $request->post('product')['id'],
            'multiplicity_id' => $request->post('multiplicity')['id'],
            'store_id' => $request->post('store')['id'],
            'remain' => $request->post('volume'),
            'volume' => $request->post('volume'),
        ]);
        $target = Target::findOrFail($target->id);
        return $target;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $target = Target::findOrFail($id);

        Validator::make((array) $target, [
            'contragent_id' => 'same:' . Auth::user()->contragents[0]->id,
        ])->validate();


        $target->update([
            'contragent_id' => Auth::user()->contragents[0]->id,
            'product_id' => $request->post('product')['id'],
            'multiplicity_id' => $request->post('multiplicity')['id'],
            'store_id' => $request->post('store')['id'],
            'remain' => $request->post('remain'),
            'volume' => $request->post('volume'),
        ]);

        $target = Target::findOrFail($id);
        return $target;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $target = Target::findOrFail($id);

        Validator::make((array) $target, [
            'contragent_id' => 'same:' . Auth::user()->contragents[0]->id,
        ])->validate();

        return $target;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $target = Target::findOrFail($id);

        if ($target->contragent_id != Auth::user()->contragents[0]->id) return '0';

        $target->delete();

        return '1';
    }
}
