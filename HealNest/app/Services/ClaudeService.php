<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class ClaudeService
{
    protected string $baseUrl = 'https://api.anthropic.com/v1';
    protected string $model = 'claude-sonnet-4-20250514';

    public function suggestFeatures(string $repoSummary, string $extraPrompt = ''): array
    {
        $apiKey = config('services.anthropic.key');

        if (! $apiKey) {
            throw new RuntimeException('Anthropic API key is not configured.');
        }

        $prompt = trim(<<<PROMPT
You are an expert product strategist and Laravel reviewer for a mental health web app called HealNest.
Based on the repository summary below, suggest 8 unique, high-impact features that would make the project stand out for a college 3rd-year presentation.
Keep suggestions feasible for a student team and avoid duplicating existing features.
For each suggestion include:
- title
- description
- uniqueness
- effort_hours
- implementation_plan
Return valid JSON only with a top-level array named "features".

Extra instructions:
{$extraPrompt}

Repository summary:
{$repoSummary}
PROMPT);

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])->post("{$this->baseUrl}/messages", [
            'model' => $this->model,
            'max_tokens' => 1400,
            'temperature' => 0.3,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('Claude request failed: ' . $response->body());
        }

        $content = data_get($response->json(), 'content.0.text', '');
        $decoded = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Claude returned invalid JSON: ' . json_last_error_msg());
        }

        return $decoded;
    }
}
