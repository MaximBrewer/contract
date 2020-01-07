<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Contragent;
use \App\Store;

class ContragentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Contragent::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contragent = Contragent::create($request->all());
        $contragent->types()->sync($request->all()['typeIds']);
        $storesIds = [];
        $contragent->update($request->all());
        if(count($request->all()['stores'])){
            foreach($request->all()['stores'] as $store){
                if($store['coords'] && $store['address']){
                    if($store['id']){
                        Store::find($store['id'])->update([
                            'contragent_id' => $contragent->id,
                            'coords' => $store['coords'], 
                            'address' => $store['address'],
                            'federal_district_id' => $store['federal_district']['id'],
                            'region_id' => $store['region']['id']
                        ]);
                        $storesIds[] = $store['id'];
                    } else {
                        $storesIds[] = Store::create([
                            'contragent_id' => $contragent->id,
                            'coords' => $store['coords'],
                            'address' => $store['address'],
                            'federal_district_id' => $store['federal_district']['id'],
                            'region_id' => $store['region']['id']
                        ])->id;
                    }
                }
            }
            Store::whereNotIn('id', $storesIds)->where("contragent_id", $contragent->id)->delete();
        }
        $contragent = Contragent::findOrFail($contragent->id);
        return $contragent;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Contragent::findOrFail($id);
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
        $contragent = Contragent::findOrFail($id);
        $contragent->types()->sync($request->all()['typeIds']);
        $storesIds = [];
        $contragent->update($request->all());
        if(count($request->all()['stores'])){
            foreach($request->all()['stores'] as $store){
                if($store['coords'] && $store['address']){
                    if($store['id']){
                        Store::find($store['id'])->update([
                            'contragent_id' => $contragent->id,
                            'coords' => $store['coords'], 
                            'address' => $store['address'], 
                            'federal_district_id' => $store['federal_district']['id'],
                            'region_id' => $store['region']['id']
                        ]);
                        $storesIds[] = $store['id'];
                    } else {
                        $storesIds[] = Store::create([
                            'contragent_id' => $contragent->id,
                            'coords' => $store['coords'],
                            'address' => $store['address'],
                            'federal_district_id' => $store['federal_district']['id'],
                            'region_id' => $store['region']['id']
                        ])->id;
                    }
                }
            }
            Store::whereNotIn('id', $storesIds)->where("contragent_id", $contragent->id)->delete();
        }
        $contragent = Contragent::findOrFail($id);
        return $contragent;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contragent = Contragent::findOrFail($id);
        $contragent->delete();
        return '';
    }
}
