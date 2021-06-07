<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Joint;
use App\Http\Resources\Joint as JointResource;
use App\User;
use Illuminate\Support\Facades\Auth;

class JointsController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return JointResource::collection(Joint::where('creditor_id', User::find(Auth::user()->id)->contragents[0]->id)->orWhere('supplier_id', User::find(Auth::user()->id)->contragents[0]->id)->get());
    }

    public function store(Request $r)
    {
        $orbits = json_encode(is_array($r->post('orbits')) ? array_map(function ($i) {
            return $i['value'];
        }, $r->post('orbits')) : []);
        if ($r->post('supplier_id')) {
            if (
                !($Joint = Joint::where('supplier_id', $r->post('supplier_id'))
                    ->where('creditor_id', User::find(Auth::user()->id)->contragents[0]->id)
                    ->first())
            )
                Joint::create([
                    'creditor_id' => User::find(Auth::user()->id)->contragents[0]->id,
                    'supplier_id' => $r->post('supplier_id'),
                    'description' => $r->post('description'),
                    'orbits' => $orbits,
                    'status' => 'distributor'
                ]);
        } elseif ($r->post('creditor_id')) {
            if (
                !($Joint = Joint::where('creditor_id', $r->post('creditor_id'))
                    ->where('supplier_id', User::find(Auth::user()->id)->contragents[0]->id)
                    ->first())
            ) {
                Joint::create([
                    'creditor_id' => $r->post('creditor_id'),
                    'supplier_id' => User::find(Auth::user()->id)->contragents[0]->id,
                    'description' => $r->post('description'),
                    'orbits' => $orbits,
                    'status' => 'manufacturer'
                ]);
            } else {
                if ($Joint->status == 'distributor')
                    $Joint->update(['status' => 'both']);
            }
        }
        return $this->index();
    }

    public function update(Request $r, $id)
    {

        $Joint = Joint::findOrFail($id);

        if ($Joint->creditor_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        $Joint->update([
            'supplier_id' => $r->post('supplier_id'),
            'description' => $r->post('description'),
        ]);

        return $this->index();
    }

    public function destroy(Request $r, $id)
    {

        $Joint = Joint::findOrFail($id);

        if ($Joint->creditor_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        Joint::findOrFail($id)->delete();
        return $this->index();
    }
}
