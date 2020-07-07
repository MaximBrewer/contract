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
use App\DisputeContragent;
use App\Contragent;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Vote;
use App\Mail\DisputeMail;

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
            'settings' => setting('site')
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
        $dispute = Dispute::findByContragents([Auth::user()->contragents[0]->id, $request->post('contragent_id')]);
        if (!$dispute) {
            $comment1 = Comment::where('writer', Auth::user()->contragents[0]->id)->where('contragent_id', $request->post('contragent_id'))->first();
            $comment2 = Comment::where('writer', $request->post('contragent_id'))->where('contragent_id', Auth::user()->contragents[0]->id)->first();
            if (!$comment1 && $comment2) {
                return response()->json([
                    'message' => __('No testimonials!'),
                    'errors' => []
                ], 422);
            }
            if (
                ($comment1 && $comment1->votes == 5) || ($comment2 && $comment2->votes == 5)
            ) {
                return response()->json([
                    'message' => __('Review is perfect, dispute is not neccessary!'),
                    'errors' => []
                ], 422);
            }
            if (
                ($comment1 && Carbon::now()->subDays(7)->timestamp < strtotime($comment1->updated_at))
                || ($comment2 && Carbon::now()->subDays(7)->timestamp < strtotime($comment2->updated_at))
            ) {
                return response()->json([
                    'message' => __('Wait seven days!'),
                    'errors' => []
                ], 422);
            }

            $message = "Приглашение на диспут между компаниями " . Auth::user()->contragents[0]->title . " и " . Contragent::findOrFail($request->post('contragent_id'))->title . ".\nЭто приглашение всех компании России принять участие в диспуте (Целевое общение) между компаниями " . Auth::user()->contragents[0]->title . " и " . Contragent::findOrFail($request->post('contragent_id'))->title . " по поводу не отличных отзывов между ними.\nВ силу того, что компании не смогли самостоятельно разрешить вопросы между собой, мы призываем каждую фирму Росси имеющую отношение к сфере Мясной промышлености РФ принять участие в диспуте (Целевое общение) и совместно принять решение о том, какие действия необходимо произвести компании " . Auth::user()->contragents[0]->title . " и компании " . Contragent::findOrFail($request->post('contragent_id'))->title . ", чтобы выйти из неприятной ситуации между ними.";

            $dispute = Dispute::create([
                'status' => 'is_open',
                'message' => $message
            ]);

            $dispute->contragents()->sync([Auth::user()->contragents[0]->id, $request->post('contragent_id')]);
            event(new DisputeEvent($dispute));
        }
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

    public function toggleVote($id, $proposal_id, Request $request)
    {
        $dispute = Dispute::findOrFail($id);
        Vote::where('contragent_id', Auth::user()->contragents[0]->id)->where('proposal_id', '<>', $proposal_id)->delete();
        $vote = Vote::where('proposal_id', $proposal_id)->where('contragent_id', Auth::user()->contragents[0]->id)->first();
        if (!$vote) {
            Vote::create([
                'contragent_id' => Auth::user()->contragents[0]->id,
                'proposal_id' => $proposal_id,
                'dispute_id' => $id,
            ]);
        } else {
            $vote->delete();
        }
        if (!$dispute->goal && (int) setting('site.dispute_target') && (int) $dispute->votes > setting('site.dispute_target')) {
            $dispute->update([
                'goal' => Carbon::now()
            ]);
        }
        event(new DisputeEvent($dispute));
        return [
            'dispute' => new DisputeResource($dispute)
        ];
    }

    public function sendEmails($id, Request $request)
    {
        $dispute = Dispute::findOrFail($id);
        if ($dispute->sent) {
            return response()->json([
                'message' => __('Messages already sent!'),
                'errors' => []
            ], 422);
        }
        $dispute->update([
            'sent' => 1,
            'message' => $request->post('message')
        ]);
        $users = User::all();
        foreach ($users as $user) {
            Mail::to($user)->send(new DisputeMail($dispute));
        }
        event(new DisputeEvent($dispute));
        return [
            'dispute' => new DisputeResource($dispute)
        ];
    }
}
