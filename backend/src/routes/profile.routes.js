import { Router } from 'express';
import { showProfile, updateProfile } from '../controllers/profile.controller.js';
import { requireAuth } from '../middleware/auth.middleware.js';

const profileRouter = Router();

profileRouter.use(requireAuth);
profileRouter.get('/', showProfile);
profileRouter.patch('/', updateProfile);

export default profileRouter;
