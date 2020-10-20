<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ContractTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContractTemplatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $templates = ContractTemplate::all();
        return $templates;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        return ContractTemplate::where(
            'contragent_id',
            Auth::user()->contragents[0]->id
        )->where(
            'contract_type_id',
            $request->get('contract_type_id')
        )->orderBy('version', 'desc')->first();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function contragent(Request $request, $id)
    {
        return DB::table('contract_templates AS a')->select(DB::raw('a.*'))
            ->leftJoin('contract_templates AS b', function ($join) {
                $join->on('a.contract_type_id', '=', 'b.contract_type_id')
                    ->on('a.version', '<', 'b.version');
            })->where(
                'a.contragent_id',
                $id
            )->whereNull(
                'b.version'
            )->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return ContractTemplate::where(
            'contragent_id',
            Auth::user()->contragents[0]->id
        )->orderBy('version', 'desc')->groupBy('version')->get();
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
            "proposer_header" => "required|min:4",
            "text" => "required|min:100",
            "title" => "required|min:6",
            "common_title" => "required|min:6",
            "recipient_title" => "required|min:6",
            "receiver_title" => "required|min:6",
        ]);

        $template = ContractTemplate::where(
            'contragent_id',
            Auth::user()->contragents[0]->id
        )->where(
            'contract_type_id',
            $request->post('contract_type_id')
        )->orderBy('version', 'desc')->first();

        $template = ContractTemplate::create([
            'contragent_id' => Auth::user()->contragents[0]->id,
            'contract_type_id' => $request->post('contract_type_id'),
            "proposer_header" => $request->post('proposer_header'),
            "common_title" => $request->post('common_title'),
            "recipient_title" => $request->post('recipient_title'),
            "receiver_title" => $request->post('receiver_title'),
            "text" => $request->post('text'),
            "title" => $request->post('title'),
            "version" => $template ? $template->version + 1 : 10,
            "accepting" => $request->post('accepting')
        ]);

        return $template;
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
