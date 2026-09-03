import {
  authenticateUser,
  createAccessToken,
  createUser,
  requestPasswordReset,
  resetPassword,
} from '../services/auth.service.js';
import env from '../config/env.js';

const publicRoles = ['user', 'parent', 'counselor'];
const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

function validationError(response, errors) {
  return response.status(422).json({ message: 'Validation failed.', errors });
}

function setAccessCookie(response, token, remember) {
  response.cookie('healnest_token', token, {
    httpOnly: true,
    sameSite: 'lax',
    secure: env.nodeEnv === 'production',
    maxAge: remember ? 30 * 24 * 60 * 60 * 1000 : 24 * 60 * 60 * 1000,
  });
}

export async function register(request, response, next) {
  try {
    const { name, email, password, passwordConfirmation, age, role } = request.body;
    const errors = {};
    if (typeof name !== 'string' || !name.trim() || name.trim().length > 100) errors.name = 'Name is required and must not exceed 100 characters.';
    if (typeof email !== 'string' || !emailPattern.test(email)) errors.email = 'A valid email address is required.';
    if (typeof password !== 'string' || password.length < 8) errors.password = 'Password must be at least 8 characters.';
    if (password !== passwordConfirmation) errors.passwordConfirmation = 'Password confirmation does not match.';
    if (!Number.isInteger(age) || age < 15 || age > 30) errors.age = 'Age must be between 15 and 30.';
    if (!publicRoles.includes(role)) errors.role = 'Role must be user, parent, or counselor.';
    if (Object.keys(errors).length) return validationError(response, errors);

    const user = await createUser({ name: name.trim(), email: email.trim().toLowerCase(), password, age, role });
    const token = createAccessToken(user);
    setAccessCookie(response, token, false);
    return response.status(201).json({ user });
  } catch (error) {
    if (error.code === '23505') return validationError(response, { email: 'Email already registered.' });
    return next(error);
  }
}

export async function login(request, response, next) {
  try {
    const { email, password, remember = false } = request.body;
    if (typeof email !== 'string' || !emailPattern.test(email) || typeof password !== 'string' || !password) {
      return validationError(response, { credentials: 'Email and password are required.' });
    }
    const user = await authenticateUser(email.trim().toLowerCase(), password);
    if (!user) return response.status(422).json({ message: 'Invalid credentials.', errors: { email: 'Invalid credentials.' } });

    const token = createAccessToken(user, Boolean(remember));
    setAccessCookie(response, token, Boolean(remember));
    return response.json({ user });
  } catch (error) {
    return next(error);
  }
}

export function logout(_request, response) {
  response.clearCookie('healnest_token', { httpOnly: true, sameSite: 'lax', secure: env.nodeEnv === 'production' });
  return response.status(204).send();
}

export function me(request, response) {
  return response.json({ user: request.user });
}

export async function forgotPassword(request, response, next) {
  try {
    const { email } = request.body;
    if (typeof email !== 'string' || !emailPattern.test(email)) return validationError(response, { email: 'A valid email address is required.' });
    await requestPasswordReset(email.trim().toLowerCase());
    return response.json({ message: 'If that email address is registered, a reset link has been sent.' });
  } catch (error) {
    return next(error);
  }
}

export async function updatePassword(request, response, next) {
  try {
    const { email, token, password, passwordConfirmation } = request.body;
    const errors = {};
    if (typeof email !== 'string' || !emailPattern.test(email)) errors.email = 'A valid email address is required.';
    if (typeof token !== 'string' || !token) errors.token = 'Reset token is required.';
    if (typeof password !== 'string' || password.length < 8) errors.password = 'Password must be at least 8 characters.';
    if (password !== passwordConfirmation) errors.passwordConfirmation = 'Password confirmation does not match.';
    if (Object.keys(errors).length) return validationError(response, errors);

    const updated = await resetPassword({ email: email.trim().toLowerCase(), token, password });
    if (!updated) return response.status(422).json({ message: 'The password reset link is invalid or expired.', errors: { email: 'The password reset link is invalid or expired.' } });
    return response.json({ message: 'Your password has been reset. Please sign in again.' });
  } catch (error) {
    return next(error);
  }
}
