<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Assessment;
use App\Models\MoodLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $userId = (string) $user->_id;

        $moodCount = MoodLog::where('user_id', $userId)->count();
        $assessmentCount = Assessment::where('user_id', $userId)->count();
        $openAlerts = Alert::where('user_id', $userId)->where('status', 'open')->count();

        $recentMoods = MoodLog::where('user_id', $userId)
            ->orderBy('logged_at', 'desc')
            ->limit(5)
            ->get();

        $latestAssessment = Assessment::where('user_id', $userId)
            ->orderBy('taken_at', 'desc')
            ->first();

        $joinedAt = $user->created_at ? Carbon::parse($user->created_at) : null;

        return view('profile.show', compact(
            'user',
            'moodCount',
            'assessmentCount',
            'openAlerts',
            'recentMoods',
            'latestAssessment',
            'joinedAt'
        ));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $userId = (string) $user->_id;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId, '_id')],
            'age' => ['nullable', 'integer', 'min:1', 'max:120'],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (!empty($validated['new_password'])) {
            if (empty($validated['current_password']) || !Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }
        }

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'age' => $validated['age'] ?? null,
        ];

        if (!empty($validated['new_password'])) {
            $updateData['password'] = Hash::make($validated['new_password']);
        }

        $user->update($updateData);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }
}
