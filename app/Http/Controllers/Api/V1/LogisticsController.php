<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Logistic;
use App\Http\Resources\Logistic as LogisticResource;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LogisticsController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return LogisticResource::collection(Logistic::all());
    }
    public function show($id)
    {
        return new LogisticResource(Logistic::findOrFail($id));
    }

    public function store(Request $r)
    {
        $validator = Validator::make($r->all(), [
            "title" => "required|min:3",
            "gosznak" => "required|min:3",
            "coords" => "required",
            "capacity.id" => "required|exists:capacities,id",
            "federal_district.id" => "required|exists:federal_districts,id",
            "region.id" => "required|exists:regions,id",
            'available_from' => 'required|date|after:today'
        ]);

        $validator->validate();

        $logistic = Logistic::create([
            'contragent_id' => User::find(Auth::user()->id)->contragents[0]->id,
            'gosznak' => $r->post('gosznak'),
            'title' => $r->post('title'),
            'address' => $r->post('address'),
            'description' => $r->post('description'),
            'capacity_id' => $r->post('capacity')['id'],
            'federal_district_id' => $r->post('federal_district')['id'],
            'region_id' => $r->post('region')['id'],
            'available_from' => date("Y-m-d", strtotime($r->post('available_from'))),
            'coords' => $r->post('coords')
        ]);
        $purposes = [];
        foreach($r->post('purposes') as $purpose) $purposes[] = $purpose['id'];

        $logistic->purposes()->sync($purposes);

        return LogisticResource::collection(Logistic::all());
    }

    public function update(Request $r, $id)
    {

        $logistic = Logistic::findOrFail($id);

        if ($logistic->contragent_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }
        
        $validator = Validator::make($r->all(), [
            "title" => "required|min:3",
            "gosznak" => "required|min:3",
            "coords" => "required",
            "capacity.id" => "required|exists:capacities,id",
            "federal_district.id" => "required|exists:federal_districts,id",
            "region.id" => "required|exists:regions,id",
            'available_from' => 'required|date|after:today'
        ]);

        $validator->validate();

        $logistic->update([
            'gosznak' => $r->post('gosznak'),
            'title' => $r->post('title'),
            'address' => $r->post('address'),
            'description' => $r->post('description'),
            'capacity_id' => $r->post('capacity')['id'],
            'federal_district_id' => $r->post('federal_district')['id'],
            'region_id' => $r->post('region')['id'],
            'available_from' => date("Y-m-d", strtotime($r->post('available_from'))),
            'coords' => $r->post('coords')
        ]);

        $purposes = [];

        foreach($r->post('purposes') as $purpose) $purposes[] = $purpose['id'];

        $logistic->purposes()->sync($purposes);

        return LogisticResource::collection(Logistic::all());

    }

    public function destroy(Request $r, $id){

        $logistic = Logistic::findOrFail($id);

        if ($logistic->contragent_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        Logistic::findOrFail($id)->delete();
        return LogisticResource::collection(Logistic::all());
    }
}
