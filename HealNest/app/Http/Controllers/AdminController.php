<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Assessment;
use App\Models\MoodLog;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers       = User::count();
        $totalAssessments = Assessment::count();
        $openAlerts       = Alert::where('status', 'open')->count();
        $totalMoodLogs    = MoodLog::count();

        $riskCounts = Assessment::raw(fn($col) => $col->aggregate([
            ['$group' => ['_id' => '$risk_level', 'count' => ['$sum' => 1]]],
        ]))->pluck('count', '_id');

        return view('admin.index', compact(
            'totalUsers', 'totalAssessments', 'openAlerts', 'totalMoodLogs', 'riskCounts'
        ));
    }

    public function users(Request $request)
    {
        $query = User::query();
        if ($request->filled('role')) $query->where('role', $request->role);
        if ($request->filled('search')) $query->where('name', 'like', '%'.$request->search.'%');
        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function updateRole(Request $request, string $id)
    {
        $request->validate(['role' => 'required|in:user,counselor,admin']);
        User::findOrFail($id)->update(['role' => $request->role]);
        return back()->with('success', 'Role updated.');
    }

    public function deleteUser(string $id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User deleted.');
    }
}
