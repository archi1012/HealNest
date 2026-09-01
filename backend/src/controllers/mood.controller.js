import pool from '../config/database.js';

function invalid(response, errors) { return response.status(422).json({ message: 'Validation failed.', errors }); }

export async function createMood(request, response, next) {
  try {
    const { mood, note = null, tags = [] } = request.body;
    if (!Number.isInteger(mood) || mood < 1 || mood > 5 || (note !== null && (typeof note !== 'string' || note.length > 500)) || !Array.isArray(tags)) {
      return invalid(response, { mood: 'Mood must be an integer between 1 and 5.' });
    }
    const normalizedTags = tags.map((tag) => String(tag).trim()).filter(Boolean).slice(0, 20);
    if (normalizedTags.some((tag) => tag.length > 100)) return invalid(response, { tags: 'Tags must not exceed 100 characters.' });
    const client = await pool.connect();
    try {
      await client.query('BEGIN');
      const created = await client.query('INSERT INTO mood_logs (user_id, mood, note) VALUES ($1, $2, $3) RETURNING id, mood, note, logged_at', [request.user.id, mood, note || null]);
      for (const tag of [...new Set(normalizedTags)]) await client.query('INSERT INTO mood_log_tags (mood_log_id, tag) VALUES ($1, $2)', [created.rows[0].id, tag]);
      await client.query('COMMIT');
      return response.status(201).json({ moodLog: { ...created.rows[0], tags: [...new Set(normalizedTags)] } });
    } catch (error) { await client.query('ROLLBACK'); throw error; } finally { client.release(); }
  } catch (error) { next(error); }
}

export async function moodHistory(request, response, next) {
  try {
    const { rows } = await pool.query(
      `SELECT log.id, log.mood, log.note, log.logged_at, COALESCE(ARRAY_AGG(tag.tag ORDER BY tag.tag) FILTER (WHERE tag.tag IS NOT NULL), ARRAY[]::VARCHAR[]) tags
       FROM mood_logs log LEFT JOIN mood_log_tags tag ON tag.mood_log_id = log.id
       WHERE log.user_id = $1 GROUP BY log.id ORDER BY log.logged_at DESC LIMIT 30`, [request.user.id]);
    const data = [...rows].reverse();
    return response.json({ logs: rows, labels: data.map((log) => new Date(log.logged_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })), moodData: data.map((log) => log.mood) });
  } catch (error) { next(error); }
}

export async function moodAnalytics(request, response, next) {
  try {
    const { rows: logs } = await pool.query(
      `SELECT log.id, log.mood, log.logged_at, COALESCE(ARRAY_AGG(tag.tag) FILTER (WHERE tag.tag IS NOT NULL), ARRAY[]::VARCHAR[]) tags
       FROM mood_logs log LEFT JOIN mood_log_tags tag ON tag.mood_log_id = log.id WHERE log.user_id = $1 GROUP BY log.id ORDER BY log.logged_at`, [request.user.id]);
    const now = Date.now(); const seven = logs.filter((log) => new Date(log.logged_at).getTime() >= now - 6 * 86400000); const thirty = logs.filter((log) => new Date(log.logged_at).getTime() >= now - 29 * 86400000);
    const average = (items) => items.length ? Number((items.reduce((sum, item) => sum + item.mood, 0) / items.length).toFixed(1)) : 0;
    const bestMood = logs.length ? Math.max(...logs.map((log) => log.mood)) : 0;
    const tagCounts = {};
    logs.flatMap((log) => log.tags).filter(Boolean).forEach((tag) => { const key = tag.trim().toLowerCase(); tagCounts[key] = (tagCounts[key] || 0) + 1; });
    return response.json({ logs, labels: thirty.map((log) => new Date(log.logged_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })), moodData: thirty.map((log) => log.mood), averageMood: average(logs), weeklyAverage: average(seven), bestMood, worstMood: logs.length ? Math.min(...logs.map((log) => log.mood)) : 0, latestMood: logs.at(-1)?.mood ?? null, moodCount: logs.length, trend: seven.length >= 2 ? Number((seven.at(-1).mood - seven[0].mood).toFixed(1)) : 0, distribution: Object.fromEntries([1, 2, 3, 4, 5].map((level) => [level, logs.filter((log) => log.mood === level).length])), tagCounts: Object.fromEntries(Object.entries(tagCounts).sort((a, b) => b[1] - a[1]).slice(0, 8)), peakDates: logs.filter((log) => log.mood === bestMood).map((log) => new Date(log.logged_at).toLocaleDateString()) });
  } catch (error) { next(error); }
}
