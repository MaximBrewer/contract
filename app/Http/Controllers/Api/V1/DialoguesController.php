<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dialogue as DialogueResource;
use App\Http\Resources\DialogueWithPrases as DialogueWithPrasesResource;
use App\Dialogue;
use App\Events\Dialog;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Phrase;
use Illuminate\Support\Facades\DB;

class DialoguesController extends Controller
{
    public function index(Request $r)
    {
        return DialogueResource::collection(Dialogue::where('contragent_1', User::find(Auth::user()->id)->contragents[0]->id)->orWhere('contragent_2', User::find(Auth::user()->id)->contragents[0]->id)->get());
    }

    public function store(Request $r)
    {
        $cid = User::find(Auth::user()->id)->contragents[0]->id;

        $dialog = Dialogue::findOrFail($r->post('id'));

        $k = $dialog->contragent_1 == $cid || $dialog->contragent_2 == $cid;

        if ($k === false) return response()->json([
            'message' => __('Haker Attack!!!'),
            'errors' => []
        ], 422);

        $phrase = Phrase::create([
            'dialogue_id' => $dialog->id,
            'contragent_id' => $cid,
            'text' => $r->post('text')
        ]);

        event(new Dialog($dialog, $phrase));

        return [$dialog, $phrase];
    }

    public function storeDialog($contragent_id)
    {
        $dialogue = DB::select("select id from dialogues where (contragent_1 = ? and contragent_2 = ?) or (contragent_1 = ? and contragent_2 = ?)", [
            $contragent_id,
            User::find(Auth::user()->id)->contragents[0]->id,
            User::find(Auth::user()->id)->contragents[0]->id,
            $contragent_id,
        ]);

        if (!$dialogue) {
            $dialogue = Dialogue::create([
                'contragent_1' => User::find(Auth::user()->id)->contragents[0]->id,
                'contragent_2' => $contragent_id
            ]);
        } else {
            $dialogue = Dialogue::find($dialogue[0]->id);
        }

        return $dialogue;
    }

    public function show(Request $r, $id)
    {
        return new DialogueWithPrasesResource(Dialogue::findOrFail($id));
    }
}
