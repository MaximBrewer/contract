<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Comment;
use App\Line;
use App\Dispute;
use App\Proposal;
use App\Http\Resources\LineResource;
use App\Http\Resources\DisputeResource;
use App\Http\Resources\ProposalResource;
use App\Events\Line as LineEvent;
use App\Events\Dispute as DisputeEvent;
use App\Events\Proposal as ProposalEvent;

class DisputesController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return [
            'disputes' => DisputeResource::collection(Dispute::all()),
            'settins' => setting('site')
        ];
    }
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return [
            'dispute' => new DisputeResource(Dispute::findOrFail($id))
        ];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $comment1 = Comment::where('writer', Auth::user()->contragents[0]->id)->where('contragent_id', $request->post('contragent_id'))->first();
        $comment2 = Comment::where('writer', Auth::user()->contragents[0]->id)->where('contragent_id', $request->post('contragent_id'))->first();

        if (
            Carbon::now()->subDays(7)->timestamp < strtotime($comment1->updated_at)
            || Carbon::now()->subDays(7)->timestamp < strtotime($comment2->updated_at)
        ) {
            return response()->json([
                'message' => __('Wait seven days!'),
                'errors' => []
            ], 422);
        }

        $dispute = Dispute::create([
            'status' => 'is_open'
        ]);

        $dispute->contragents()->sync([Auth::user()->contragents[0]->id, $request->post('contragent_id')]);
        event(new DisputeEvent($dispute));
        return [
            'dispute' => new DisputeResource($dispute)
        ];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function lineStore($id, Request $request)
    {
        $dispute = Dispute::findOrFail($id);

        $line = Line::create([
            'dispute_id' => $dispute->id,
            'contragent_id' => Auth::user()->contragents[0]->id,
            'user_id' => Auth::user()->id,
            'message' => $request->post('message')
        ]);
        event(new LineEvent($line));
        return [
            'line' => new LineResource($line)
        ];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function lineUpdate($id, $line_id, Request $request)
    {
        $dispute = Dispute::findOrFail($id);
        $line = Line::findOrFail($line_id);

        $line->update([
            'message' => $request->post('message')
        ]);
        event(new LineEvent($line));
        return [
            'line' => new LineResource($line)
        ];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function lineDelete($id, $line_id, Request $request)
    {
        $dispute = Dispute::findOrFail($id);
        $line = Line::findOrFail($line_id);

        if (Auth::user()->contragents[0]->id == $line->contragent_id && $dispute->id == $line->dispute_id) {
            event(new LineEvent($line->id));
            $line->delete();
        }
        return [];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function proposalStore($id, Request $request)
    {
        $dispute = Dispute::findOrFail($id);
        Proposal::create([
            'dispute_id' => $dispute->id,
            'contragent_id' => Auth::user()->contragents[0]->id,
            'user_id' => Auth::user()->id,
            'message' => $request->post('message')
        ]);
        event(new DisputeEvent($dispute));
        return [
            'dispute' => new DisputeResource($dispute)
        ];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function proposalUpdate($id, $proposal_id, Request $request)
    {
        $dispute = Dispute::findOrFail($id);
        $proposal = Proposal::findOrFail($proposal_id);

        $proposal->update([
            'message' => $request->post('message')
        ]);
        event(new DisputeEvent($dispute));
        return [
            'dispute' => new DisputeResource($dispute)
        ];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function proposalDelete($id, $proposal_id, Request $request)
    {
        $dispute = Dispute::findOrFail($id);
        $proposal = Proposal::findOrFail($proposal_id);

        if (Auth::user()->contragents[0]->id == $proposal->contragent_id && $dispute->id == $proposal->dispute_id) {
            event(new DisputeEvent($dispute));
            $proposal->delete();
        }
        return [
            'dispute' => new DisputeResource($dispute)
        ];
    }
}
