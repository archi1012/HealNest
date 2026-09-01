import bcrypt from 'bcryptjs';
import pool from '../config/database.js';

const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const safeUserColumns = 'id, name, email, age, role, parent_id, email_verified_at, created_at, updated_at';

function validationError(response, errors) {
  return response.status(422).json({ message: 'Validation failed.', errors });
}

export async function showProfile(request, response, next) {
  try {
    const userId = request.user.id;
    const [moodCount, assessmentCount, openAlerts, recentMoods, latestAssessment] = await Promise.all([
      pool.query('SELECT COUNT(*)::int AS count FROM mood_logs WHERE user_id = $1', [userId]),
      pool.query('SELECT COUNT(*)::int AS count FROM assessments WHERE user_id = $1', [userId]),
      pool.query("SELECT COUNT(*)::int AS count FROM alerts WHERE user_id = $1 AND status = 'open'", [userId]),
      pool.query(
        `SELECT mood_log.id, mood_log.mood, mood_log.note, mood_log.logged_at,
                COALESCE(ARRAY_AGG(tag.tag ORDER BY tag.tag) FILTER (WHERE tag.tag IS NOT NULL), ARRAY[]::VARCHAR[]) AS tags
         FROM mood_logs AS mood_log
         LEFT JOIN mood_log_tags AS tag ON tag.mood_log_id = mood_log.id
         WHERE mood_log.user_id = $1
         GROUP BY mood_log.id
         ORDER BY mood_log.logged_at DESC
         LIMIT 5`,
        [userId],
      ),
      pool.query(
        `SELECT id, type, score, risk_level, taken_at
         FROM assessments WHERE user_id = $1
         ORDER BY taken_at DESC LIMIT 1`,
        [userId],
      ),
    ]);

    return response.json({
      user: request.user,
      moodCount: moodCount.rows[0].count,
      assessmentCount: assessmentCount.rows[0].count,
      openAlerts: openAlerts.rows[0].count,
      recentMoods: recentMoods.rows,
      latestAssessment: latestAssessment.rows[0] || null,
      joinedAt: request.user.created_at,
    });
  } catch (error) {
    return next(error);
  }
}

export async function updateProfile(request, response, next) {
  try {
    const { name, email, age, currentPassword, newPassword, newPasswordConfirmation } = request.body;
    const errors = {};
    if (typeof name !== 'string' || !name.trim() || name.trim().length > 255) errors.name = 'Name is required and must not exceed 255 characters.';
    if (typeof email !== 'string' || !emailPattern.test(email) || email.length > 255) errors.email = 'A valid email address is required.';
    if (age !== null && age !== undefined && (!Number.isInteger(age) || age < 1 || age > 120)) errors.age = 'Age must be between 1 and 120.';
    if (newPassword !== undefined && newPassword !== null && newPassword !== '') {
      if (typeof currentPassword !== 'string' || !currentPassword) errors.currentPassword = 'Current password is required.';
      if (typeof newPassword !== 'string' || newPassword.length < 8) errors.newPassword = 'New password must be at least 8 characters.';
      if (newPassword !== newPasswordConfirmation) errors.newPasswordConfirmation = 'Password confirmation does not match.';
    }
    if (Object.keys(errors).length) return validationError(response, errors);

    const normalizedEmail = email.trim().toLowerCase();
    const { rows: existingRows } = await pool.query(
      'SELECT id, password_hash FROM users WHERE id = $1',
      [request.user.id],
    );
    const existingUser = existingRows[0];
    if (!existingUser) return response.status(401).json({ message: 'Authentication required.' });

    let passwordHash = null;
    if (newPassword) {
      if (!(await bcrypt.compare(currentPassword, existingUser.password_hash))) {
        return validationError(response, { currentPassword: 'Current password is incorrect.' });
      }
      passwordHash = await bcrypt.hash(newPassword, 12);
    }

    const { rows } = await pool.query(
      `UPDATE users
       SET name = $1, email = $2, age = $3, password_hash = COALESCE($4, password_hash)
       WHERE id = $5
       RETURNING ${safeUserColumns}`,
      [name.trim(), normalizedEmail, age ?? null, passwordHash, request.user.id],
    );

    return response.json({ user: rows[0], message: 'Profile updated successfully.' });
  } catch (error) {
    if (error.code === '23505') return validationError(response, { email: 'Email already registered.' });
    return next(error);
  }
}
