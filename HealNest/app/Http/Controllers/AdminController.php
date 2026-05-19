<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Assessment;
use App\Models\MoodLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ─── Dashboard Analytics ────────────────────────────────────────────────

    public function index()
    {
        $totalUsers       = User::count();
        $totalAssessments = Assessment::count();
        $openAlerts       = Alert::where('status', 'open')->count();
        $totalMoodLogs    = MoodLog::count();
        $totalCounselors  = User::where('role', 'counselor')->count();

        // Risk distribution
        $riskCounts = Assessment::count() > 0
            ? Assessment::raw(fn($c) => $c->aggregate([
                ['$group' => ['_id' => '$risk_level', 'count' => ['$sum' => 1]]],
              ]))->pluck('count', '_id')
            : collect();

        // Role distribution
        $roleCounts = User::raw(fn($c) => $c->aggregate([
            ['$group' => ['_id' => '$role', 'count' => ['$sum' => 1]]],
        ]))->pluck('count', '_id');

        // Registrations last 7 days
        $regLabels = collect();
        $regData   = collect();
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $regLabels->push($day->format('M d'));
            $regData->push(User::where('created_at', '>=', $day->copy()->startOfDay())
                               ->where('created_at', '<=', $day->copy()->endOfDay())
                               ->count());
        }

        // Assessments last 7 days
        $assLabels = collect();
        $assData   = collect();
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $assLabels->push($day->format('M d'));
            $assData->push(Assessment::where('taken_at', '>=', $day->copy()->startOfDay())
                                     ->where('taken_at', '<=', $day->copy()->endOfDay())
                                     ->count());
        }

        return view('admin.index', compact(
            'totalUsers', 'totalAssessments', 'openAlerts', 'totalMoodLogs',
            'totalCounselors', 'riskCounts', 'roleCounts',
            'regLabels', 'regData', 'assLabels', 'assData'
        ));
    }

    // ─── User Management ────────────────────────────────────────────────────

    public function users(Request $request)
    {
        $query = User::query();
        if ($request->filled('role'))   $query->where('role', $request->role);
        if ($request->filled('search')) $query->where('name', 'like', '%' . $request->search . '%');
        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email',
            'password' => 'required|min:8',
            'age'      => 'required|integer|min:10|max:100',
            'role'     => 'required|in:user,parent,counselor,admin',
        ]);

        if (User::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'Email already exists.'])->withInput();
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'age'      => (int) $request->age,
            'role'     => $request->role,
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function editUser(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email',
            'age'   => 'required|integer|min:10|max:100',
            'role'  => 'required|in:user,parent,counselor,admin',
        ]);

        $data = $request->only('name', 'email', 'age', 'role');
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function deleteUser(string $id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User deleted.');
    }

    public function updateRole(Request $request, string $id)
    {
        $request->validate(['role' => 'required|in:user,parent,counselor,admin']);
        User::findOrFail($id)->update(['role' => $request->role]);
        return back()->with('success', 'Role updated.');
    }

    // ─── Counselor Management ───────────────────────────────────────────────

    public function counselors()
    {
        $counselors = User::where('role', 'counselor')->orderBy('created_at', 'desc')->get();

        // Attach alert counts per counselor (resolved by them — approximation via open alerts)
        $alertStats = Alert::raw(fn($c) => $c->aggregate([
            ['$group' => ['_id' => '$status', 'count' => ['$sum' => 1]]],
        ]))->pluck('count', '_id');

        return view('admin.counselors', compact('counselors', 'alertStats'));
    }

    public function promoteToCounselor(string $id)
    {
        User::findOrFail($id)->update(['role' => 'counselor']);
        return back()->with('success', 'User promoted to counselor.');
    }

    public function demoteCounselor(string $id)
    {
        User::findOrFail($id)->update(['role' => 'user']);
        return back()->with('success', 'Counselor demoted to user.');
    }

    // ─── Reports ────────────────────────────────────────────────────────────

    public function reports(Request $request)
    {
        $period = $request->get('period', '7');
        $from   = Carbon::today()->subDays((int) $period);

        $newUsers       = User::where('created_at', '>=', $from)->count();
        $newAssessments = Assessment::where('taken_at', '>=', $from)->count();
        $newMoodLogs    = MoodLog::where('logged_at', '>=', $from)->count();
        $newAlerts      = Alert::where('created_at', '>=', $from)->count();

        // Severe/moderate cases
        $highRiskCount = Assessment::whereIn('risk_level', ['severe', 'moderate'])
            ->where('taken_at', '>=', $from)->count();

        // Recent assessments with user info
        $recentAssessments = Assessment::where('taken_at', '>=', $from)
            ->orderBy('taken_at', 'desc')->limit(20)->get()
            ->map(function ($a) {
                $a->user = User::find($a->user_id);
                return $a;
            });

        // Recent alerts
        $recentAlerts = Alert::where('created_at', '>=', $from)
            ->orderBy('created_at', 'desc')->limit(20)->get()
            ->map(function ($a) {
                $a->user = User::find($a->user_id);
                return $a;
            });

        return view('admin.reports', compact(
            'period', 'newUsers', 'newAssessments', 'newMoodLogs',
            'newAlerts', 'highRiskCount', 'recentAssessments', 'recentAlerts'
        ));
    }
}
