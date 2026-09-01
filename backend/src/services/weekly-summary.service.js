import nodemailer from 'nodemailer';
import env from '../config/env.js';
import pool from '../config/database.js';

export async function sendWeeklySummaries() {
  if (!env.smtp.host || !env.smtp.user || !env.smtp.password) throw new Error('SMTP configuration is required to send weekly summaries.');
  const transport = nodemailer.createTransport({ host: env.smtp.host, port: env.smtp.port, secure: env.smtp.port === 465, auth: { user: env.smtp.user, pass: env.smtp.password } });
  const { rows: users } = await pool.query("SELECT id, name, email FROM users WHERE role IN ('user', 'parent')");
  for (const user of users) {
    const { rows } = await pool.query("SELECT COUNT(*)::int mood_count, COALESCE(ROUND(AVG(mood)::numeric, 1), 0) average_mood, COUNT(*) FILTER (WHERE logged_at >= NOW() - INTERVAL '7 days')::int week_moods FROM mood_logs WHERE user_id = $1", [user.id]);
    const stats = rows[0];
    await transport.sendMail({ from: env.smtp.from, to: user.email, subject: 'Your Weekly HealNest Summary', text: `Hello ${user.name},\n\nThis week you logged ${stats.week_moods} mood check-ins. Your all-time average mood is ${stats.average_mood}/5 across ${stats.mood_count} entries.\n\nVisit HealNest to continue your wellness journey.` });
  }
  return users.length;
}
