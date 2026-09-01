import { Router } from 'express';
import {
  forgotPassword,
  login,
  logout,
  me,
  register,
  updatePassword,
} from '../controllers/auth.controller.js';
import { requireAuth } from '../middleware/auth.middleware.js';

const authRouter = Router();

authRouter.post('/register', register);
authRouter.post('/login', login);
authRouter.post('/logout', logout);
authRouter.get('/me', requireAuth, me);
authRouter.post('/forgot-password', forgotPassword);
authRouter.post('/reset-password', updatePassword);

export default authRouter;
