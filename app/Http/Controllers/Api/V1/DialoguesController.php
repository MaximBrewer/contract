<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Phrase as PhraseResource;
use App\Http\Resources\Dialogue as DialogueResource;
use App\Http\Resources\DialogueWithPrases as DialogueWithPrasesResource;
use App\Dialogue;
use App\DialogueContragent;
use App\Events\Dialog;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Contragent;
use App\Phrase;
use Illuminate\Support\Facades\DB;

class DialoguesController extends Controller
{
    public function index(Request $r)
    {
        return DialogueResource::collection(Dialogue::whereIn('id', function ($query) {
            $query->select('dialogue_id')
                ->from(with(new DialogueContragent())->getTable())
                ->where('contragent_id', User::find(Auth::user()->id)->contragents[0]->id);
        })->get());
    }

    public function store(Request $r)
    {
        $dialog = Dialogue::findOrFail($r->post('id'));
        $dialogContragents = DialogueContragent::where('dialogue_id', $dialog->id)->orderBy('id', 'asc')->get();

        $contragent_ids = [
            $dialogContragents[0]->contragent_id,
            $dialogContragents[1]->contragent_id,
        ];

        $k = array_search(User::find(Auth::user()->id)->contragents[0]->id, $contragent_ids);

        if ($k === false) return response()->json([
            'message' => __('Haker Attack!!!'),
            'errors' => []
        ], 422);

        $phrase = Phrase::create([
            'dialogue_contragent_id' => $dialogContragents[$k]->id,
            'text' => $r->post('text'),
            'sent' => 1
        ]);
        
        event(new Dialog($dialog, $phrase));
        
        return $phrase;


    }

    public function show(Request $r, $id)
    {
        return new DialogueWithPrasesResource(Dialogue::findOrFail($id));
    }
}
