<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deals;
use App\Models\Threads;
use Inertia\Inertia;
use Inertia\Response;

class DealController extends Controller
{

    public function index(Request $request, $dealId)
    {

        return Inertia::render('Sales/Deal',[

            'dealId' => $dealId,
        ]);
    }


    public function store(Request $request, Threads $thread)
    {
        $user = $request->user();

        // only assigned rep or manager
        if (
            $thread->assigned_to !== $user->id
        ) {
            abort(403);
        }

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'value_amount' => ['nullable', 'numeric'],
            'value_currency' => ['nullable', 'string', 'max:3'],
        ]);

        $deal = Deals::create([
            'user_id' => $thread->user_id,
            'owner_id' => $user->id,
            'thread_id' => $thread->id,
            'title' => $data['title'],
            'stage' => 'new_lead',
            'value_amount' => $data['value_amount'] ?? null,
            'value_currency' => $data['value_currency'] ?? 'USD',
        ]);

        return response()->json([
            'deal' => $deal,
        ]);
    }

    public function show(Deals $deal)
    {

        return response()->json([
            'deal' => $deal->load([
                'user:id,name,email',
                'owner:id,name',
                'notes.author:id,name',
                'stageHistory.changedBy:id,name',
            ]),
        ]);
    }

    public function addNote(Request $request, Deals $deal)
    {
        $data = $request->validate([
            'body' => ['required', 'string'],
        ]);

        $note = $deal->notes()->create([
            'body' => $data['body'],
            'author_id' => auth()->id(),
        ]);

        return response()->json($note->load('author:id,name'));
    }

    public function updateStage(Request $request, Deals $deal)
    {
        $data = $request->validate([
            'stage' => ['required', 'string'],
        ]);

        $old = $deal->stage;

        $deal->update([
            'stage' => $data['stage'],
        ]);

        $deal->stageHistory()->create([
            'deal_id' => $deal->id,
            'from_stage' => $old,
            'to_stage' => $data['stage'],
            'changed_by' => auth()->id(),
        ]);

        return response()->json([
            'deal' => $deal,
        ]);

    }

    public function reassignOwner(Request $request, Deals $deal)
    {
        $data = $request->validate([
            'owner_id' => ['required', 'exists:users,id'],
        ]);

        $deal->update([
            'owner_id' => $data['owner_id'],
        ]);

        return response()->json($deal->load('owner:id,name'));
    }
}
