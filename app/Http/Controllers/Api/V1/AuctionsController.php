<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Auction;
use Illuminate\Support\Facades\Auth;
use \App\Contragent;
use \App\Target;
use Illuminate\Validation\Rule;
use \App\Store;
use Illuminate\Support\Facades\Validator;


class AuctionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $action = null)
    {

        switch ($action) {
            case "all":
                return Auction::all();
                break;
            case "my":
                return Auction::where('contragent_id', Auth::user()->contragents[0]->id)->get();
                break;
            case "bid":
                return Auth::user()->contragents[0]->auctions;
                break;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeManager(Request $request)
    {


        $request->validate([
            "multiplicity.id" => "required|exists:multiplicities,id",
            "product.id" => "required|exists:products,id",
            "store.id" => "required|exists:stores,id",
            "start_at" => "required",
            "finish_at" => "required|after:start_at",
            "comment" => "",
            "start_price" => "required",
            "volume" => "required",
            "step" => "required",
        ]);


        $auction = Auction::create([
            'contragent_id' => $request->post('contragent')['id'],
            'multiplicity_id' => $request->post('multiplicity')['id'],
            'product_id' => $request->post('product')['id'],
            'store_id' => $request->post('store')['id'],
            'start_at' => date('Y-m-d H:i:s', strtotime($request->post('start_at'))),
            'finish_at' => date('Y-m-d H:i:s', strtotime($request->post('finish_at'))),
            'comment' => $request->post('comment'),
            'start_price' => $request->post('start_price'),
            'volume' => $request->post('volume'),
            'step' => $request->post('step'),
        ]);

        $auction = Auction::findOrFail($auction->id);
        return $auction;
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
            "start_at" => "required",
            "finish_at" => "required|after:start_at",
            "comment" => "",
            "start_price" => "required",
            "volume" => "required",
            "step" => "required",
        ]);

        $auction = Auction::create([
            'contragent_id' => Auth::user()->contragents[0]->id,
            'multiplicity_id' => $request->post('multiplicity')['id'],
            'product_id' => $request->post('product')['id'],
            'store_id' => $request->post('store')['id'],
            'start_at' => date('Y-m-d H:i:s', strtotime($request->post('start_at'))),
            'finish_at' => date('Y-m-d H:i:s', strtotime($request->post('finish_at'))),
            'comment' => $request->post('comment'),
            'start_price' => $request->post('start_price'),
            'volume' => $request->post('volume'),
            'step' => $request->post('step'),
        ]);

        $auction = Auction::findOrFail($auction->id);
        return $auction;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Auction::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bid(Request $request, $action, $id)
    {

        if (count(Auth::user()->contragents)) Auth::user()->contragents[0]->auctions()->attach($id);
        return $this->index($request, $action);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unbid(Request $request, $action, $id)
    {
        if (count(Auth::user()->contragents)) Auth::user()->contragents[0]->auctions()->detach($id);
        return $this->index($request, $action);
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

        $auction = Auction::findOrFail($id);

        if ($auction->contragent_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        $request->validate([
            "multiplicity.id" => "required|exists:multiplicities,id",
            "product.id" => "required|exists:products,id",
            "store.id" => "required|exists:stores,id",
            "start_at" => "required",
            "finish_at" => "required|after:start_at",
            "comment" => "",
            "start_price" => "required",
            "volume" => "required",
            "step" => "required",
        ]);

        $auction->update([
            'product_id' => $request->post('product')['id'],
            'multiplicity_id' => $request->post('multiplicity')['id'],
            'store_id' => $request->post('store')['id'],
            'start_at' => date('Y-m-d H:i:s', strtotime($request->post('start_at'))),
            'finish_at' => date('Y-m-d H:i:s', strtotime($request->post('finish_at'))),
            'comment' => $request->post('comment'),
            'start_price' => $request->post('start_price'),
            'volume' => $request->post('volume'),
        ]);
        
        $auction = Auction::findOrFail($id);
        return $auction;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateManager(Request $request, $id)
    {
        $auction = Auction::findOrFail($id);
        $auction->update([
            'contragent_id' => $request->post('contragent')['id'],
            'product_id' => $request->post('product')['id'],
            'multiplicity_id' => $request->post('multiplicity')['id'],
            'store_id' => $request->post('store')['id'],
            'start_at' => date('Y-m-d H:i:s', strtotime($request->post('start_at'))),
            'finish_at' => date('Y-m-d H:i:s', strtotime($request->post('finish_at'))),
            'comment' => $request->post('comment'),
            'start_price' => $request->post('start_price'),
            'volume' => $request->post('volume'),
        ]);
        $auction = Auction::findOrFail($id);
        return $auction;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addBidder(Request $request)
    {
        $auction = Auction::findOrFail($request->post('auction'));
        $contragent = Contragent::findOrFail($request->post('bidder'));
        $contragent->auctions()->attach($auction->id);
        $auction = Auction::findOrFail($request->post('auction'));
        return $auction;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $auction = Auction::findOrFail($id);
        $auction->delete();
        return '';
    }
}
