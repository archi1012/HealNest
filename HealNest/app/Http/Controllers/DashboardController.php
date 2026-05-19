<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Assessment;
use App\Models\MoodLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $userId = (string) $user->_id;

        $recentMoods = MoodLog::where('user_id', $userId)
            ->orderBy('logged_at', 'desc')->limit(7)->get();

        $avgMood = $recentMoods->avg('mood') ?? 0;

        // Streak: consecutive days with a log
        $streak = 0;
        $day = now()->startOfDay();
        while (MoodLog::where('user_id', $userId)
            ->where('logged_at', '>=', $day->copy())
            ->where('logged_at', '<', $day->copy()->addDay())
            ->exists()) {
            $streak++;
            $day->subDay();
        }

        $latestAssessment = Assessment::where('user_id', $userId)
            ->orderBy('taken_at', 'desc')->first();

        $openAlerts = Alert::where('user_id', $userId)->where('status', 'open')->count();

        $moodLabels = $recentMoods->sortBy('logged_at')
            ->pluck('logged_at')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'));
        $moodData   = $recentMoods->sortBy('logged_at')->pluck('mood');

        return view('dashboard.index', compact(
            'user', 'avgMood', 'streak', 'latestAssessment', 'openAlerts', 'moodLabels', 'moodData'
        ));
    }
}
