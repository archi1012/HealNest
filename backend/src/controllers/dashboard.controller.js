import pool from '../config/database.js';

export async function dashboard(request, response, next) {
  try {
    const userId = request.user.id;
    const [moods, streakLogs, latestAssessment, alerts] = await Promise.all([
      pool.query('SELECT mood, logged_at FROM mood_logs WHERE user_id = $1 ORDER BY logged_at DESC LIMIT 7', [userId]),
      pool.query('SELECT DISTINCT logged_at::date AS day FROM mood_logs WHERE user_id = $1 ORDER BY day DESC', [userId]),
      pool.query('SELECT id, type, score, risk_level, taken_at FROM assessments WHERE user_id = $1 ORDER BY taken_at DESC LIMIT 1', [userId]),
      pool.query("SELECT COUNT(*)::int AS count FROM alerts WHERE user_id = $1 AND status = 'open'", [userId]),
    ]);
    const recentMoods = moods.rows;
    let streak = 0;
    const days = new Set(streakLogs.rows.map((mood) => new Date(mood.day).toISOString().slice(0, 10)));
    for (let day = new Date();; day.setUTCDate(day.getUTCDate() - 1)) {
      if (!days.has(day.toISOString().slice(0, 10))) break;
      streak += 1;
    }
    const chronologicalMoods = [...recentMoods].reverse();
    return response.json({
      user: request.user,
      averageMood: recentMoods.length ? Number((recentMoods.reduce((sum, mood) => sum + mood.mood, 0) / recentMoods.length).toFixed(1)) : 0,
      streak,
      latestAssessment: latestAssessment.rows[0] || null,
      openAlerts: alerts.rows[0].count,
      moodLabels: chronologicalMoods.map((mood) => new Date(mood.logged_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
      moodData: chronologicalMoods.map((mood) => mood.mood),
    });
  } catch (error) { next(error); }
}
