<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Joint;
use App\Http\Resources\Joint as JointResource;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Contragent;

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
        if ($r->post('supplier_id')) {
            $joint = Joint::where('supplier_id', $r->post('supplier_id'))
                ->where('creditor_id', User::find(Auth::user()->id)->contragents[0]->id)
                ->first();
            if (!$joint)
                Joint::create([
                    'creditor_id' => User::find(Auth::user()->id)->contragents[0]->id,
                    'supplier_id' => $r->post('supplier_id'),
                    'status' => 'distributor'
                ]);
            else {
                if ($joint->status == 'manufacturer')
                    $joint->update([
                        'status' => 'both'
                    ]);
            }
        } elseif ($r->post('creditor_id')) {
            $joint = Joint::where('creditor_id', $r->post('creditor_id'))
                ->where('supplier_id', User::find(Auth::user()->id)->contragents[0]->id)
                ->first();
            if (!$joint) {
                Joint::create([
                    'creditor_id' => $r->post('creditor_id'),
                    'supplier_id' => User::find(Auth::user()->id)->contragents[0]->id,
                    'status' => 'manufacturer'
                ]);
            } else {
                if ($joint->status == 'distributor')
                    $joint->update([
                        'status' => 'both'
                    ]);
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

    public function check(Request $r)
    {
        $joint = Joint::where('creditor_id', $r->post('id'))
            ->where('supplier_id', User::find(Auth::user()->id)->contragents[0]->id)
            ->first();
        if (!$joint) {
            Joint::create([
                'creditor_id' => $r->post('id'),
                'supplier_id' => User::find(Auth::user()->id)->contragents[0]->id,
                'status' => 'manufacturer'
            ]);
            return ['contragent' => null];
        } else {
            if ($joint->status == 'distributor')
                $joint->update([
                    'status' => 'both',
                ]);
            return ['contragent' => Contragent::findOrFail($r->post('id'))];
        }
    }

    public function destroy(Request $r, $id)
    {
        $joint = Joint::findOrFail($id);
        if ($joint->creditor_id == Auth::user()->contragents[0]->id) {
            if ($joint->status == 'both') {
                $joint->update([
                    'status' => 'manufacturer',
                ]);
            } elseif ($joint->status == 'distributor') {
                $joint->delete();
            }
        } elseif ($joint->supplier_id == Auth::user()->contragents[0]->id) {
            if ($joint->status == 'both') {
                $joint->update([
                    'status' => 'distributor',
                ]);
            } elseif ($joint->status == 'manufacturer') {
                $joint->delete();
            }
        } else {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }
        return $this->index();
    }
}
