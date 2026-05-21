<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Assessment;
use App\Models\Intervention;
use App\Models\User;
use Illuminate\Http\Request;

class CounselorController extends Controller
{
    public function index()
    {
        $alerts = Alert::where('status', 'open')->orderBy('created_at', 'desc')->get();
        $flaggedUserIds = $alerts->pluck('user_id')->unique()->values();
        $users  = User::whereIn('_id', $flaggedUserIds->toArray())->get();
        return view('counselor.index', compact('alerts', 'users'));
    }

    /**
     * Show a simple directory of available counselors (dummy data).
     */
    public function connect()
    {
        $counselors = collect([
            [
                'id' => 'c1',
                'name' => 'Dr. Maya Alvarez',
                'specialty' => 'Depression & Anxiety',
                'bio' => 'Clinical counselor with 8 years experience supporting young adults.',
                'phone' => '+10000000001'
            ],
            [
                'id' => 'c2',
                'name' => 'Samuel Okoye',
                'specialty' => 'Adolescent Counseling',
                'bio' => 'Specialist in adolescent mental health and family dynamics.',
                'phone' => '+10000000002'
            ],
            [
                'id' => 'c3',
                'name' => 'Aisha Rahman',
                'specialty' => 'Crisis Support',
                'bio' => 'Experienced in short-term crisis intervention and safety planning.',
                'phone' => '+10000000003'
            ],
        ]);

        return view('counselor.connect', compact('counselors'));
    }

    public function userDetail(string $userId)
    {
        $user        = User::findOrFail($userId);
        $assessments = Assessment::where('user_id', $userId)->orderBy('taken_at', 'desc')->get();
        $alerts      = Alert::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        $notes       = Intervention::where('user_id', $userId)->where('type', 'note')
            ->orderBy('created_at', 'desc')->get();
        return view('counselor.user', compact('user', 'assessments', 'alerts', 'notes'));
    }

    public function storeNote(Request $request, string $userId)
    {
        $request->validate(['content' => 'required|string|max:1000']);
        Intervention::create([
            'user_id' => $userId,
            'type'    => 'note',
            'content' => $request->content,
        ]);
        return back()->with('success', 'Note saved.');
    }

    public function resolveAlert(string $id)
    {
        Alert::findOrFail($id)->update(['status' => 'resolved']);
        return back()->with('success', 'Alert resolved.');
    }
}
