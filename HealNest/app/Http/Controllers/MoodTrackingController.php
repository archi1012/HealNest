<?php

namespace App\Http\Controllers;

use App\Models\MoodLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MoodTrackingController extends Controller
{
    public function create()
    {
        return view('mood.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mood' => 'required|integer|min:1|max:5',
            'note' => 'nullable|string|max:500',
            'tags' => 'nullable|string',
        ]);

        MoodLog::create([
            'user_id'   => (string) Auth::user()->_id,
            'mood'      => (int) $data['mood'],
            'note'      => $data['note'] ?? null,
            'tags'      => isset($data['tags']) && $data['tags']
                            ? array_map('trim', explode(',', $data['tags']))
                            : [],
            'logged_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Mood logged successfully!');
    }

    public function history()
    {
        $userId = (string) Auth::user()->_id;

        $logs = MoodLog::where('user_id', $userId)
            ->orderBy('logged_at', 'desc')->limit(30)->get();

        $sorted = $logs->sortBy('logged_at');
        $labels = $sorted->pluck('logged_at')->map(fn($d) => Carbon::parse($d)->format('M d'));
        $data   = $sorted->pluck('mood');

        return view('mood.history', compact('logs', 'labels', 'data'));
    }
}
