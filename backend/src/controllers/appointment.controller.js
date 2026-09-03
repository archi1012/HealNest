import pool from '../config/database.js';

const validStatuses = ['pending', 'confirmed', 'completed', 'cancelled'];
const invalid = (response, errors) => response.status(422).json({ message: 'Validation failed.', errors });

export async function appointments(request, response, next) {
  try {
    const [counselors, bookings, counts] = await Promise.all([
      pool.query("SELECT id, name, email FROM users WHERE role = 'counselor' ORDER BY name"),
      pool.query('SELECT appointment.*, counselor.name counselor_name FROM appointments appointment JOIN users counselor ON counselor.id = appointment.counselor_id WHERE appointment.user_id = $1 ORDER BY appointment.scheduled_at DESC', [request.user.id]),
      pool.query("SELECT COUNT(*) FILTER (WHERE status IN ('pending', 'confirmed') AND scheduled_at >= NOW())::int upcoming, COUNT(*) FILTER (WHERE status = 'completed')::int completed FROM appointments WHERE user_id = $1", [request.user.id]),
    ]);
    response.json({ counselors: counselors.rows, appointments: bookings.rows, upcomingCount: counts.rows[0].upcoming, completedCount: counts.rows[0].completed });
  } catch (error) { next(error); }
}

export async function createAppointment(request, response, next) {
  try {
    const { counselorId, scheduledAt, meetingType, reason, notes = null } = request.body;
    const date = new Date(scheduledAt);
    if (typeof counselorId !== 'string' || Number.isNaN(date.getTime()) || date < new Date() || !['virtual', 'in-person'].includes(meetingType) || typeof reason !== 'string' || !reason.trim() || reason.length > 500 || (notes !== null && (typeof notes !== 'string' || notes.length > 1000))) return invalid(response, { appointment: 'Provide a counselor, future time, meeting type, and reason.' });
    const counselor = await pool.query("SELECT id FROM users WHERE id = $1 AND role = 'counselor'", [counselorId]);
    if (!counselor.rowCount) return invalid(response, { counselorId: 'Selected counselor is unavailable.' });
    const { rows } = await pool.query('INSERT INTO appointments (user_id, counselor_id, scheduled_at, meeting_type, reason, notes) VALUES ($1, $2, $3, $4, $5, $6) RETURNING *', [request.user.id, counselorId, date.toISOString(), meetingType, reason.trim(), notes || null]);
    response.status(201).json({ appointment: rows[0] });
  } catch (error) { next(error); }
}

export async function manageAppointments(request, response, next) {
  try {
    const status = request.query.status;
    if (status && !validStatuses.includes(status)) return invalid(response, { status: 'Invalid appointment status.' });
    const { rows } = await pool.query(
      `SELECT appointment.*, user_account.name user_name, counselor.name counselor_name
       FROM appointments appointment JOIN users user_account ON user_account.id = appointment.user_id JOIN users counselor ON counselor.id = appointment.counselor_id
       ${status ? 'WHERE appointment.status = $1' : ''} ORDER BY appointment.scheduled_at DESC`, status ? [status] : []);
    const counts = await pool.query("SELECT status, COUNT(*)::int count FROM appointments GROUP BY status");
    response.json({ appointments: rows, counts: Object.fromEntries(counts.rows.map((row) => [row.status, row.count])) });
  } catch (error) { next(error); }
}

export async function updateAppointmentStatus(request, response, next) {
  try {
    const { status, counselorNotes } = request.body;
    if (!validStatuses.includes(status) || (counselorNotes !== undefined && counselorNotes !== null && (typeof counselorNotes !== 'string' || counselorNotes.length > 500))) return invalid(response, { status: 'Provide a valid status and optional counselor notes.' });
    const { rows } = await pool.query('UPDATE appointments SET status = $1, counselor_notes = COALESCE($2, counselor_notes) WHERE id = $3 RETURNING *', [status, counselorNotes ?? null, request.params.id]);
    return rows[0] ? response.json({ appointment: rows[0] }) : response.status(404).json({ message: 'Appointment not found.' });
  } catch (error) { next(error); }
}
