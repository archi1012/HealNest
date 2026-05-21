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

    public function analytics()
    {
        $userId = (string) Auth::user()->_id;

        $logs = MoodLog::where('user_id', $userId)
            ->orderBy('logged_at', 'asc')
            ->get();

        $last7Days = MoodLog::where('user_id', $userId)
            ->where('logged_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->orderBy('logged_at', 'asc')
            ->get();

        $last30Days = MoodLog::where('user_id', $userId)
            ->where('logged_at', '>=', Carbon::now()->subDays(29)->startOfDay())
            ->orderBy('logged_at', 'asc')
            ->get();

        $labels = $last30Days->map(fn ($log) => Carbon::parse($log->logged_at)->format('M d'));
        $moodData = $last30Days->pluck('mood');

        $averageMood = $logs->avg('mood') ? round($logs->avg('mood'), 1) : 0;
        $weeklyAverage = $last7Days->avg('mood') ? round($last7Days->avg('mood'), 1) : 0;
        $bestMood = $logs->max('mood') ?? 0;
        $worstMood = $logs->min('mood') ?? 0;
        $latestMood = $logs->last()->mood ?? null;
        $moodCount = $logs->count();

        $trend = $last7Days->count() >= 2
            ? round((float) $last7Days->last()->mood - (float) $last7Days->first()->mood, 1)
            : 0;

        $distribution = collect([1, 2, 3, 4, 5])->mapWithKeys(function ($level) use ($logs) {
            return [$level => $logs->where('mood', $level)->count()];
        });

        $tagCounts = $logs
            ->pluck('tags')
            ->flatten()
            ->filter()
            ->map(fn ($tag) => strtolower(trim($tag)))
            ->countBy()
            ->sortDesc()
            ->take(8);

        $peakDates = $logs
            ->where('mood', $bestMood)
            ->map(fn ($log) => Carbon::parse($log->logged_at)->format('M d, Y'))
            ->values();

        return view('mood.analytics', compact(
            'logs',
            'labels',
            'moodData',
            'averageMood',
            'weeklyAverage',
            'bestMood',
            'worstMood',
            'latestMood',
            'moodCount',
            'trend',
            'distribution',
            'tagCounts',
            'peakDates'
        ));
    }
}
