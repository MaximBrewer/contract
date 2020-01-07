<?php

namespace App\Http\Controllers\Manager;

use Illuminate\Http\Request;
use App\Contragent;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ContragentsController extends Controller
{

    private $perPage = 5;
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getIndex()
    {
        return view('manager/index');
    }

    public function getData(Request $request)
    {
        $attributes = [];

        // if((string)$request->post('window_title')){
        //     $windows = Contragent::where('title', "like", "%" . $request->post('window_title') . "%")->paginate($this->perPage);
        // }elseif((string)$request->post('eurocode')){
        //     $windows = Contragent::where('eurocode', "like", $request->post('eurocode') . "%")->paginate($this->perPage);
        // }else{
            // if((int)$request->post('car_body_id')){
            //     $attributes['car_body_id'] = $request->post('car_body_id');
            // }elseif((int)$request->post('car_model_id')){
            //     $attributes['car_model_id'] = $request->post('car_model_id');
            // }elseif((int)$request->post('car_producer_id')){
            //     $attributes['car_producer_id'] = $request->post('car_producer_id');
            // }
            // if((int)$request->post('window_type_id')){
            //     $attributes['window_type_id'] = $request->post('window_type_id');
            // }
            $windows = Contragent::where($attributes)->paginate($this->perPage);
        // }

        //var_dump($attributes);die;

        $windows->withPath(url()->full());
        
        return response()->json($windows);
    }
}
