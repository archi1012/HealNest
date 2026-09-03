import { findSafeUserById, verifyAccessToken } from '../services/auth.service.js';

function extractToken(request) {
  const authorization = request.get('authorization');
  if (authorization?.startsWith('Bearer ')) return authorization.slice(7);
  return request.cookies?.healnest_token;
}

export async function requireAuth(request, response, next) {
  try {
    const token = extractToken(request);
    if (!token) return response.status(401).json({ message: 'Authentication required.' });

    const claims = verifyAccessToken(token);
    const user = await findSafeUserById(claims.sub);
    if (!user) return response.status(401).json({ message: 'Authentication required.' });

    request.user = user;
    return next();
  } catch {
    return response.status(401).json({ message: 'Authentication required.' });
  }
}

export function requireRole(...roles) {
  return (request, response, next) => {
    if (!request.user || !roles.includes(request.user.role)) {
      return response.status(403).json({ message: 'Unauthorized.' });
    }
    return next();
  };
}
