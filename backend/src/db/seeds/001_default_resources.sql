INSERT INTO resources (title, category, icon, description)
SELECT seed.title, seed.category, seed.icon, seed.description
FROM (
    VALUES
        ('Breathing Exercises', 'Coping', '🌬️', 'Simple 4-7-8 breathing to calm anxiety in minutes.'),
        ('Grounding Techniques', 'Coping', '🌱', 'The 5-4-3-2-1 method to anchor yourself in the present.'),
        ('Sleep Hygiene Tips', 'Wellness', '🌙', 'Build a consistent sleep routine for better mental health.'),
        ('Journaling for Mental Health', 'Self-Care', '📓', 'How expressive writing reduces stress and boosts mood.'),
        ('Understanding Depression', 'Education', '📘', 'Signs, symptoms, and when to seek professional help.'),
        ('Managing Social Anxiety', 'Education', '🤝', 'Practical strategies for social situations.'),
        ('Mindfulness Meditation', 'Wellness', '🧘', 'A beginner''s guide to daily mindfulness practice.'),
        ('Crisis Helplines', 'Emergency', '📞', 'Immediate support contacts available 24/7.')
) AS seed(title, category, icon, description)
WHERE NOT EXISTS (
    SELECT 1 FROM resources existing WHERE existing.title = seed.title
);
