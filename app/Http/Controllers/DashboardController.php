<?php

namespace App\Http\Controllers;

use App\Models\Deals;
use App\Models\Threads;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
     /**
     * Client dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        return Inertia::render('Dashboard', [
            'stats' => [
                'unassigned_threads' => Threads::whereNull('assigned_to')->count(),

                'my_threads' => Threads::where(
                    'assigned_to',
                    $user->id
                )->count(),

                'my_deals' => Deals::where(
                    'owner_id',
                    $user->id
                )->count(),
            ],

            'unassignedThreads' => Threads::query()
                ->whereNull('assigned_to')
                ->with('user:id,name,email')
                ->latest('last_message_at')
                ->limit(10)
                ->get(),

            'myThreads' => Threads::query()
                ->where('assigned_to', $user->id)
                ->with('user:id,name,email')
                ->latest('last_message_at')
                ->limit(10)
                ->get(),

            'myDeals' => Deals::query()
                ->where('owner_id', $user->id)
                ->with('user:id,name')
                ->latest()
                ->limit(10)
                ->get(),
        ]);
    }

    public function show(Threads $thread)
    {
        // Ensure the sales rep owns this thread
        if (
            $thread->assigned_to !== auth()->id()
        ) {
            abort(403);
        }

        return Inertia::render('Sales/Threads/Show', [
            'thread' => $thread->load([
                'user',
                'assignedUser',
                'messages.sender',
            ]),
        ]);
    }

    public function store(Request $request, Threads $thread)
    {
        $user = $request->user();

        // authorization
        if (
            $thread->assigned_to !== $user->id
        ) {
            abort(403);
        }

        $data = $request->validate([
            'body' => ['required', 'string'],
        ]);

        $message = $thread->messages()->create([
            'sender_id' => $user->id,
            'sender_type' => 'team',
            'body' => $data['body'],
        ]);

        // update thread last activity
        $thread->update([
            'last_message_at' => now(),
        ]);

        return response()->json([
            'message' => $message,
        ]);
    }
}
