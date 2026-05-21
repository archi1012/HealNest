<?php

namespace App\Http\Controllers;

use App\Models\Resource;

class ResourcesController extends Controller
{
    public function index()
    {
        $resources = Resource::orderBy('created_at', 'desc')->get();

        if ($resources->isNotEmpty()) {
            return view('resources.index', compact('resources'));
        }

        $resources = collect([
            ['title' => 'Breathing Exercises', 'category' => 'Coping', 'icon' => '🌬️',
             'desc'  => 'Simple 4-7-8 breathing to calm anxiety in minutes.'],
            ['title' => 'Grounding Techniques', 'category' => 'Coping', 'icon' => '🌱',
             'desc'  => 'The 5-4-3-2-1 method to anchor yourself in the present.'],
            ['title' => 'Sleep Hygiene Tips', 'category' => 'Wellness', 'icon' => '🌙',
             'desc'  => 'Build a consistent sleep routine for better mental health.'],
            ['title' => 'Journaling for Mental Health', 'category' => 'Self-Care', 'icon' => '📓',
             'desc'  => 'How expressive writing reduces stress and boosts mood.'],
            ['title' => 'Understanding Depression', 'category' => 'Education', 'icon' => '📘',
             'desc'  => 'Signs, symptoms, and when to seek professional help.'],
            ['title' => 'Managing Social Anxiety', 'category' => 'Education', 'icon' => '🤝',
             'desc'  => 'Practical strategies for social situations.'],
            ['title' => 'Mindfulness Meditation', 'category' => 'Wellness', 'icon' => '🧘',
             'desc'  => 'A beginner\'s guide to daily mindfulness practice.'],
            ['title' => 'Crisis Helplines', 'category' => 'Emergency', 'icon' => '📞',
             'desc'  => 'Immediate support contacts available 24/7.'],
        ]);

        return view('resources.index', compact('resources'));
    }
}
