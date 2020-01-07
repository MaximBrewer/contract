<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Auction;
use \App\Store;

class AuctionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Auction::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auction = Auction::create($request->all());
        $auction->types()->sync($request->all()['typeIds']);
        $storesIds = [];
        $auction->update($request->all());
        if(count($request->all()['stores'])){
            foreach($request->all()['stores'] as $store){
                if($store['coords'] && $store['address']){
                    if($store['id']){
                        Store::find($store['id'])->update([
                            'coords' => $store['coords'], 
                            'address' => $store['address']
                        ]);
                        $storesIds[] = $store['id'];
                    } else {
                        $storesIds[] = Store::create([
                            'contragent_id' => $auction->id,
                            'coords' => $store['coords'],
                            'address' => $store['address'],
                        ])->id;
                    }
                }
            }
            Store::whereNotIn('id', $storesIds)->where("contragent_id", $auction->id)->delete();
        }
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
    public function update(Request $request, $id)
    {
        $auction = Auction::findOrFail($id);
        $auction->types()->sync($request->all()['typeIds']);
        $storesIds = [];
        $auction->update($request->all());
        if(count($request->all()['stores'])){
            foreach($request->all()['stores'] as $store){
                if($store['coords'] && $store['address']){
                    if($store['id']){
                        Store::find($store['id'])->update([
                            'coords' => $store['coords'], 
                            'address' => $store['address']
                        ]);
                        $storesIds[] = $store['id'];
                    } else {
                        $storesIds[] = Store::create([
                            'contragent_id' => $auction->id,
                            'coords' => $store['coords'],
                            'address' => $store['address'],
                        ])->id;
                    }
                }
            }
            Store::whereNotIn('id', $storesIds)->where("contragent_id", $auction->id)->delete();
        }
        $auction = Auction::findOrFail($id);
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
