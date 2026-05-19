<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    private array $phq9 = [
        'Little interest or pleasure in doing things',
        'Feeling down, depressed, or hopeless',
        'Trouble falling or staying asleep, or sleeping too much',
        'Feeling tired or having little energy',
        'Poor appetite or overeating',
        'Feeling bad about yourself',
        'Trouble concentrating on things',
        'Moving or speaking slowly (or being fidgety/restless)',
        'Thoughts that you would be better off dead',
    ];

    private array $gad7 = [
        'Feeling nervous, anxious, or on edge',
        'Not being able to stop or control worrying',
        'Worrying too much about different things',
        'Trouble relaxing',
        'Being so restless that it is hard to sit still',
        'Becoming easily annoyed or irritable',
        'Feeling afraid, as if something awful might happen',
    ];

    public function show(string $type)
    {
        $type      = strtoupper($type);
        $questions = $type === 'PHQ9' ? $this->phq9 : $this->gad7;
        return view('assessment.show', compact('type', 'questions'));
    }

    public function store(Request $request, string $type)
    {
        $type      = strtoupper($type);
        $questions = $type === 'PHQ9' ? $this->phq9 : $this->gad7;
        $count     = count($questions);

        $rules = [];
        for ($i = 0; $i < $count; $i++) {
            $rules["q{$i}"] = 'required|integer|min:0|max:3';
        }
        $validated = $request->validate($rules);

        $responses = array_values($validated);
        $score     = array_sum($responses);
        $risk      = Assessment::calcRisk($type, $score);

        $assessment = Assessment::create([
            'user_id'    => (string) Auth::id(),
            'type'       => $type,
            'responses'  => $responses,
            'score'      => $score,
            'risk_level' => $risk,
            'taken_at'   => now(),
        ]);

        if ($score >= 10) {
            Alert::create([
                'user_id'      => (string) Auth::id(),
                'triggered_by' => $assessment->_id,
                'risk_level'   => $risk,
                'status'       => 'open',
            ]);
        }

        return redirect()->route('assessment.result', $assessment->_id)
            ->with('success', 'Assessment completed!');
    }

    public function result(string $id)
    {
        $assessment = Assessment::findOrFail($id);
        return view('assessment.result', compact('assessment'));
    }
}
