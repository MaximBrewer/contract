<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Settlement;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Http\Resources\Settlement as SettlementResource;

class SettlementsController extends Controller
{
    public function index(Request $r)
    {
        $settlements = Settlement::where('contragent_id', User::find(Auth::user()->id)->contragents[0]->id)->where('status', 'done')->orderBy('id', 'desc')->get();
        return SettlementResource::collection($settlements);
    }
    public function pay(Request $r)
    {

        $settlement = Settlement::create([
            'contragent_id' => User::find(Auth::user()->id)->contragents[0]->id,
            'balance' => $r->post('balance'),
            'method' => $r->post('method'),
            'type' => 'credit',
            'status' => 'processing'
        ]);
        if ($settlement->method == 'yandex') {
            return [
                'url' => 'https://money.yandex.ru/quickpay/confirm.xml',
                'hiddens' => [
                    'receiver' => '41001300074234',
                    'label' => $settlement->id,
                    'quickpay-form' => 'donate',
                    'targets' => 'Транзакция ' . $settlement->id,
                    'paymentType' => 'AC',
                    'sum' => $settlement->balance
                ]
            ];
        } elseif ($settlement->method == 'invoice') {
            return [
                'url' => '/personal/invoice',
                'hiddens' => [
                    'id' => $settlement->id
                ]
            ];
        }
    }
}
