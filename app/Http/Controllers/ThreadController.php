<?php

namespace App\Http\Controllers;


use App\Models\Threads;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function assign(Request $request, Threads $thread)
    {
        // Optional: only sales reps/managers
        if (! $request->user()->hasAnyRole(['sales', 'manager', 'super-admin'])) {
            abort(403);
        }

        // Prevent double assignment
        if ($thread->assigned_to !== null) {
            return response()->json([
                'message' => 'Thread already assigned.',
            ], 422);
        }

        $thread->update([
            'assigned_to' => $request->user()->id,
            'status' => 'open',
        ]);

        return response()->json([
            'message' => 'Thread assigned successfully.',
            'thread' => $thread->fresh(),
        ]);
    }
}
