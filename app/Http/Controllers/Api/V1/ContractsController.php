<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Contract;
use App\ContractTemplate;
use App\Contragent;
use App\User;
use App\Http\Resources\Contract as ContractResource;

class ContractsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function signMy(Request $request, $id)
    {
        //
        $contract = Contract::findOrFail($id);
        if ($contract->contractTemplate->contragent_id != Auth::user()->contragents[0]->id)
            return Contragent::findOrFail(0);
        $contract->update([
            'status' => $contract->status == 0 ? 2 : ($contract->status == 1 ? 3 : $contract->status)
        ]);
        return ['contract' => new ContractResource($contract)];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function unsignMy(Request $request, $id)
    {
        //
        $contract = Contract::findOrFail($id);
        if ($contract->contractTemplate->contragent_id != Auth::user()->contragents[0]->id)
            return Contragent::findOrFail(0);
        $contract->update([
            'status' => 4
        ]);
        return ['contract' => new ContractResource($contract)];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sign(Request $request, $id)
    {
        $contract = Contract::findOrFail($id);
        if ($contract->contragent_id != Auth::user()->contragents[0]->id)
            return Contragent::findOrFail(0);
        $contract->update([
            'status' => $contract->status == 0 ? 1 : ($contract->status == 2 ? 3 : $contract->status)
        ]);
        return ['contract' => new ContractResource($contract)];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function unsign(Request $request, $id)
    {
        //
        $contract = Contract::findOrFail($id);
        if ($contract->contragent_id != Auth::user()->contragents[0]->id)
            return Contragent::findOrFail(0);
        $contract->update([
            'status' => 5
        ]);
        return ['contract' => new ContractResource($contract)];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function my()
    {
        $contracts = DB::table('contracts AS c')
            ->select(DB::raw('c.*, co.title as recipient, ct.version as version'))
            ->leftJoin('contract_templates AS ct', function ($join) {
                $join->on('c.contract_template_id', '=', 'ct.id');
            })
            ->leftJoin('contragents AS co', function ($join) {
                $join->on('ct.contragent_id', '=', 'co.id');
            })->where(
                'ct.contragent_id',
                Auth::user()->contragents[0]->id
            )->orderBy('c.created_at', 'DESC')->get();

        $contracts = $contracts->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'acceptor_header' => $item->acceptor_header,
                'acceptor' => $item->acceptor_header,
                'contract_template_id' => $item->contract_template_id,
                'contragent_id' => $item->contragent_id,
                'recipient' => $item->recipient,
                'statusText' => Contract::getStatus($item->status),
                'status' => $item->status,
                'date' => $item->created_at,
                'version' => $item->version
            ];
        });


        return ['contracts' => $contracts];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tome()
    {
        $contracts = Contract::where("contragent_id", Auth::user()->contragents[0]->id)->orderBy('created_at', 'DESC')->get();
        return ['contracts' => ContractResource::collection($contracts)];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            "acceptor_header" => "required|min:4",
            "contract_template_id" => "required|exists:contract_templates,id"
        ]);

        $contractTemplate = ContractTemplate::find($request->post('contract_template_id'));


        $contract = Contract::create([
            'contract_template_id' => $contractTemplate->id,
            "acceptor_header" => $request->post('acceptor_header'),
            "status" => $contractTemplate->accepting ? 2 : 0,
            "contragent_id" => Auth::user()->contragents[0]->id
        ]);

        return $contract;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
