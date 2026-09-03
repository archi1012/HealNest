import bcrypt from 'bcryptjs';
import crypto from 'node:crypto';
import jwt from 'jsonwebtoken';
import nodemailer from 'nodemailer';
import env from '../config/env.js';
import pool from '../config/database.js';

const safeUserColumns = 'id, name, email, age, role, parent_id, email_verified_at, created_at, updated_at';

export function toSafeUser(user) {
  return user ? { ...user } : null;
}

export function createAccessToken(user, remember = false) {
  return jwt.sign(
    { sub: user.id, role: user.role, email: user.email },
    env.jwtSecret,
    { expiresIn: remember ? env.jwtRememberExpiresIn : env.jwtExpiresIn },
  );
}

export function verifyAccessToken(token) {
  return jwt.verify(token, env.jwtSecret);
}

export async function findSafeUserById(id) {
  const { rows } = await pool.query(`SELECT ${safeUserColumns} FROM users WHERE id = $1`, [id]);
  return rows[0] || null;
}

export async function createUser({ name, email, password, age, role }) {
  const passwordHash = await bcrypt.hash(password, 12);
  const { rows } = await pool.query(
    `INSERT INTO users (name, email, password_hash, age, role)
     VALUES ($1, $2, $3, $4, $5)
     RETURNING ${safeUserColumns}`,
    [name, email, passwordHash, age, role],
  );
  return rows[0];
}

export async function authenticateUser(email, password) {
  const { rows } = await pool.query(
    `SELECT ${safeUserColumns}, password_hash FROM users WHERE email = $1`,
    [email],
  );
  const user = rows[0];

  if (!user || !(await bcrypt.compare(password, user.password_hash))) {
    return null;
  }

  delete user.password_hash;
  return user;
}

function hashResetToken(token) {
  return crypto.createHash('sha256').update(token).digest('hex');
}

function createMailTransport() {
  if (!env.smtp.host || !env.smtp.user || !env.smtp.password) return null;

  return nodemailer.createTransport({
    host: env.smtp.host,
    port: env.smtp.port,
    secure: env.smtp.port === 465,
    auth: { user: env.smtp.user, pass: env.smtp.password },
  });
}

export async function requestPasswordReset(email) {
  const { rows } = await pool.query('SELECT id, name, email FROM users WHERE email = $1', [email]);
  const user = rows[0];
  if (!user) return;

  const recent = await pool.query(
    `SELECT 1 FROM password_reset_tokens
     WHERE email = $1 AND created_at > NOW() - INTERVAL '60 seconds'`,
    [user.email],
  );
  if (recent.rowCount) return;

  const token = crypto.randomBytes(32).toString('hex');
  await pool.query(
    `INSERT INTO password_reset_tokens (email, token_hash, expires_at, created_at)
     VALUES ($1, $2, NOW() + INTERVAL '60 minutes', NOW())
     ON CONFLICT (email) DO UPDATE
       SET token_hash = EXCLUDED.token_hash, expires_at = EXCLUDED.expires_at, created_at = EXCLUDED.created_at`,
    [user.email, hashResetToken(token)],
  );

  const transport = createMailTransport();
  if (!transport) return;

  const resetUrl = new URL('/reset-password', env.frontendUrl);
  resetUrl.searchParams.set('token', token);
  resetUrl.searchParams.set('email', user.email);

  await transport.sendMail({
    from: env.smtp.from,
    to: user.email,
    subject: 'Reset your HealNest password',
    text: `Hello ${user.name},\n\nReset your HealNest password: ${resetUrl}\n\nIf you did not request this, you can safely ignore this email.`,
  });
}

export async function resetPassword({ email, token, password }) {
  const result = await pool.query(
    `SELECT token_hash FROM password_reset_tokens
     WHERE email = $1 AND expires_at > NOW()`,
    [email],
  );
  const resetRecord = result.rows[0];

  if (!resetRecord || !crypto.timingSafeEqual(
    Buffer.from(resetRecord.token_hash, 'hex'),
    Buffer.from(hashResetToken(token), 'hex'),
  )) {
    return false;
  }

  const passwordHash = await bcrypt.hash(password, 12);
  const client = await pool.connect();
  try {
    await client.query('BEGIN');
    await client.query('UPDATE users SET password_hash = $1, remember_token = NULL WHERE email = $2', [passwordHash, email]);
    await client.query('DELETE FROM password_reset_tokens WHERE email = $1', [email]);
    await client.query('COMMIT');
    return true;
  } catch (error) {
    await client.query('ROLLBACK');
    throw error;
  } finally {
    client.release();
  }
}
