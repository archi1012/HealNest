import { Router } from 'express';
import * as admin from '../controllers/admin.controller.js';
import { requireAuth, requireRole } from '../middleware/auth.middleware.js';
const router = Router(); router.use(requireAuth, requireRole('admin'));
router.get('/', admin.adminDashboard); router.get('/users', admin.listUsers); router.post('/users', admin.createUser); router.get('/users/:id', admin.getUser); router.patch('/users/:id', admin.updateUser); router.delete('/users/:id', admin.deleteUser); router.patch('/users/:id/role', admin.updateRole);
router.get('/resources', admin.listAdminResources); router.post('/resources', admin.createResource); router.get('/resources/:id', admin.getResource); router.patch('/resources/:id', admin.updateResource); router.delete('/resources/:id', admin.deleteResource);
router.get('/counselors', admin.counselors); router.patch('/counselors/:id/promote', (request, response, next) => { request.body.role = 'counselor'; next(); }, admin.updateRole); router.patch('/counselors/:id/demote', (request, response, next) => { request.body.role = 'user'; next(); }, admin.updateRole); router.get('/reports', admin.reports);
export default router;
