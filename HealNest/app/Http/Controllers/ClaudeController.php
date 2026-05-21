<?php

namespace App\Http\Controllers;

use App\Services\ClaudeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ClaudeController extends Controller
{
    public function index()
    {
        [$summary, $files] = $this->buildRepoSummary();

        return view('admin.ai.index', [
            'repoSummary' => $summary,
            'files' => $files,
            'result' => null,
            'extraPrompt' => '',
        ]);
    }

    public function analyze(Request $request, ClaudeService $claudeService)
    {
        $validated = $request->validate([
            'extra_prompt' => ['nullable', 'string', 'max:2000'],
        ]);

        [$summary, $files] = $this->buildRepoSummary();
        $result = $claudeService->suggestFeatures($summary, $validated['extra_prompt'] ?? '');

        return view('admin.ai.index', [
            'repoSummary' => $summary,
            'files' => $files,
            'result' => $result,
            'extraPrompt' => $validated['extra_prompt'] ?? '',
        ])->with('success', 'Claude analysis completed successfully.');
    }

    protected function buildRepoSummary(): array
    {
        $paths = [
            base_path('README.md'),
            base_path('routes/web.php'),
            base_path('app/Http/Controllers/DashboardController.php'),
            base_path('app/Http/Controllers/MoodTrackingController.php'),
            base_path('app/Http/Controllers/AssessmentController.php'),
            base_path('app/Http/Controllers/AppointmentController.php'),
            base_path('app/Http/Controllers/AdminController.php'),
            base_path('app/Models/User.php'),
            base_path('app/Models/MoodLog.php'),
            base_path('app/Models/Assessment.php'),
            base_path('app/Models/Alert.php'),
            base_path('app/Models/Appointment.php'),
            base_path('app/Models/Resource.php'),
            base_path('resources/views/dashboard/index.blade.php'),
            base_path('resources/views/mood/history.blade.php'),
            base_path('resources/views/mood/analytics.blade.php'),
            base_path('resources/views/appointments/index.blade.php'),
            base_path('resources/views/admin/index.blade.php'),
            base_path('resources/views/admin/resources/index.blade.php'),
            base_path('resources/views/resources/index.blade.php'),
        ];

        $files = [];
        $summaryParts = [];

        foreach ($paths as $path) {
            if (! File::exists($path)) {
                continue;
            }

            $relative = Str::after($path, base_path() . DIRECTORY_SEPARATOR);
            $content = File::get($path);
            $snippet = Str::limit(preg_replace('/\s+/', ' ', trim($content)), 2200, '...');

            $files[] = [
                'path' => $relative,
                'chars' => strlen($content),
            ];

            $summaryParts[] = "FILE: {$relative}\n{$snippet}";
        }

        $summary = implode("\n\n---\n\n", $summaryParts);

        return [$summary, $files];
    }
}
