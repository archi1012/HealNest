<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = (string) $user->_id;

        $counselors = User::where('role', 'counselor')
            ->orderBy('name')
            ->get();

        $appointments = Appointment::where('user_id', $userId)
            ->orderBy('scheduled_at', 'desc')
            ->get();

        $upcomingCount = Appointment::where('user_id', $userId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('scheduled_at', '>=', Carbon::now())
            ->count();

        $completedCount = Appointment::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        return view('appointments.index', compact(
            'counselors',
            'appointments',
            'upcomingCount',
            'completedCount'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $userId = (string) $user->_id;

        $validated = $request->validate([
            'counselor_id' => [
                'required',
                Rule::exists('users', '_id')->where(fn ($query) => $query->where('role', 'counselor')),
            ],
            'scheduled_at' => ['required', 'date_format:Y-m-d\TH:i', 'after_or_equal:now'],
            'meeting_type' => ['required', Rule::in(['virtual', 'in-person'])],
            'reason' => ['required', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        Appointment::create([
            'user_id' => $userId,
            'counselor_id' => $validated['counselor_id'],
            'scheduled_at' => Carbon::parse($validated['scheduled_at']),
            'meeting_type' => $validated['meeting_type'],
            'reason' => $validated['reason'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('appointments.index')->with('success', 'Appointment request sent successfully.');
    }

    public function manage(Request $request)
    {
        $query = Appointment::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderBy('scheduled_at', 'desc')->paginate(15);

        $counselorIds = $appointments->pluck('counselor_id')->merge($appointments->pluck('user_id'))->unique()->filter()->values();
        $users = User::whereIn('_id', $counselorIds->toArray())->get()->keyBy(fn ($user) => (string) $user->_id);

        $pendingCount = Appointment::where('status', 'pending')->count();
        $confirmedCount = Appointment::where('status', 'confirmed')->count();
        $completedCount = Appointment::where('status', 'completed')->count();
        $cancelledCount = Appointment::where('status', 'cancelled')->count();

        return view('appointments.manage', compact(
            'appointments',
            'users',
            'pendingCount',
            'confirmedCount',
            'completedCount',
            'cancelledCount'
        ));
    }

    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'completed', 'cancelled'])],
            'counselor_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->update([
            'status' => $validated['status'],
            'counselor_notes' => $validated['counselor_notes'] ?? $appointment->counselor_notes,
        ]);

        return back()->with('success', 'Appointment updated successfully.');
    }
}
