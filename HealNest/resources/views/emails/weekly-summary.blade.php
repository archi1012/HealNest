<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Lato', Arial, sans-serif; background: #F5F0E8; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 16px; overflow: hidden; }
        .header { background: #2D5016; color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .body { padding: 30px; }
        .stat { background: #F5F0E8; border-radius: 8px; padding: 15px; margin: 10px 0; display: flex; justify-content: space-between; }
        .footer { background: #2D5016; color: #C4A96B; text-align: center; padding: 15px; font-size: 12px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🌿 Your Weekly Summary</h1>
        <p style="margin:5px 0 0; color:#C4A96B;">HealNest – Week of {{ now()->startOfWeek()->format('M d') }}</p>
    </div>
    <div class="body">
        <p>Hi <strong>{{ $user->name }}</strong>,</p>
        <p>Here's a summary of your mental wellness activity this week:</p>

        <div class="stat">
            <span>😊 Average Mood</span>
            <strong>{{ number_format($stats['avg_mood'] ?? 0, 1) }}/5</strong>
        </div>
        <div class="stat">
            <span>📝 Mood Logs</span>
            <strong>{{ $stats['log_count'] ?? 0 }} entries</strong>
        </div>
        <div class="stat">
            <span>📋 Assessments Taken</span>
            <strong>{{ $stats['assessment_count'] ?? 0 }}</strong>
        </div>
        <div class="stat">
            <span>🔥 Current Streak</span>
            <strong>{{ $stats['streak'] ?? 0 }} days</strong>
        </div>

        <p style="margin-top:20px; color:#666;">Keep tracking your mood daily for better insights. You're doing great!</p>
        <a href="{{ url('/dashboard') }}"
           style="display:inline-block; background:#2D5016; color:white; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:bold; margin-top:10px;">
            View Dashboard
        </a>
    </div>
    <div class="footer">© {{ date('Y') }} HealNest. You're receiving this because you're a registered user.</div>
</div>
</body>
</html>
