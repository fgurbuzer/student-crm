<?php

namespace App\Http\Controllers;

use App\Models\Deals;
use App\Models\Messages;
use App\Models\Threads;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;

class ClientController extends Controller
{
    /**
     * Client dashboard.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        $threads = Threads::query()
            ->where('user_id', auth()->id())
            ->with([
                'assignedUser:id,name',
            ])
            ->latest('last_message_at')
            ->get();

        $deals = Deals::query()
            ->where('user_id', $request->user()->id)
            ->whereNotIn('stage', ['won', 'lost'])
            ->with([
                'owner:id,name',
            ])
            ->latest()
            ->get();

        return Inertia::render('Client/Dashboard', [
            'threads' => $threads,
            'deals' => $deals,
            'stats' => [
                'threads_count' => Threads::query()
                    ->where('user_id', $user->id)
                    ->count(),
            ],
        ]);
    }


    public function clients(Request $request)
    {

        return Inertia::render('Client/Index');
    }

    public function show(User $user)
    {
        $user->load([
            'userInfo',
        ]);

        $threads = $user->threads()
            ->with('assignedUser')
            ->latest()
            ->get();

        $deals = $user->deals()
            ->with('owner')
            ->latest()
            ->get();

        return Inertia::render('Client/Show', [
            'client' => $user,
            'threads' => $threads,
            'deals' => $deals,
        ]);
    }

    /**
     * Create a new thread.
     */
    public function createThread(Request $request)
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $thread = Threads::create([
            'user_id' => $request->user()->id,
            'assigned_to' => null, // temporary
            'subject' => $data['subject'],
            'status' => 'pending',
            'last_message_at' => now(),
        ]);

        Messages::create([
            'thread_id' => $thread->id,
            'sender_id' => $request->user()->id,
            'sender_type' => 'client',
            'body' => $data['message'],
        ]);

        return redirect()->route(
            'client.threads.show',
            $thread
        );
    }


    /**
     * Get thread details.
     */
    public function getThread(
        Request $request,
        Threads $thread
    ): Response {
        abort_unless(
            $thread->user_id === $request->user()->id,
            403
        );

        return Inertia::render('Client/Threads/Show', [
            'thread' => $thread->load([
                'assignedUser:id,name',
            ]),
        ]);
    }

    /**
     * Get thread messages.
     */
    public function getThreadMessages(
        Request $request,
        Threads $thread
    ) {
        abort_unless(
            $thread->user_id === $request->user()->id,
            403
        );

        return Messages::query()
            ->where('thread_id', $thread->id)
            ->with('sender:id,name')
            ->orderBy('created_at')
            ->paginate(50);
    }

    public function sendMessage(
        Request $request,
        Threads $thread
    ) {
        abort_unless(
            $thread->user_id === $request->user()->id,
            403
        );

        $data = $request->validate([
            'body' => ['required', 'string'],
        ]);

        $message = Messages::create([
            'thread_id' => $thread->id,
            'sender_id' => $request->user()->id,
            'sender_type' => 'client',
            'body' => $data['body'],
        ]);

        $thread->update([
            'last_message_at' => now(),
        ]);

        return response()->json([
            'message' => $message,
        ]);
    }

}
