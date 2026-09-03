import cors from 'cors';
import cookieParser from 'cookie-parser';
import express from 'express';
import helmet from 'helmet';
import env from './config/env.js';
import healthRouter from './routes/health.routes.js';
import authRouter from './routes/auth.routes.js';
import profileRouter from './routes/profile.routes.js';
import dashboardRouter from './routes/dashboard.routes.js';
import moodRouter from './routes/mood.routes.js';
import assessmentRouter from './routes/assessment.routes.js';
import resourceRouter from './routes/resource.routes.js';
import appointmentRouter from './routes/appointment.routes.js';
import messageRouter from './routes/message.routes.js';
import counselorRouter from './routes/counselor.routes.js';
import adminRouter from './routes/admin.routes.js';

const app = express();

app.disable('x-powered-by');
app.use(helmet());
app.use(cors({ origin: env.frontendOrigins, credentials: true }));
app.use(cookieParser());
app.use(express.json());

app.use('/api/v1', healthRouter);
app.use('/api/v1/auth', authRouter);
app.use('/api/v1/profile', profileRouter);
app.use('/api/v1/dashboard', dashboardRouter);
app.use('/api/v1/moods', moodRouter);
app.use('/api/v1/assessments', assessmentRouter);
app.use('/api/v1/resources', resourceRouter);
app.use('/api/v1/appointments', appointmentRouter);
app.use('/api/v1/messages', messageRouter);
app.use('/api/v1/counselor', counselorRouter);
app.use('/api/v1/admin', adminRouter);

app.use((request, response) => {
  response.status(404).json({ message: `Route not found: ${request.method} ${request.path}` });
});

app.use((error, _request, response, _next) => {
  console.error(error);
  if (error.code === '22P02' || error.code === '23503') {
    return response.status(422).json({ message: 'One or more supplied values are invalid.' });
  }
  if (error.type === 'entity.parse.failed') {
    return response.status(400).json({ message: 'Invalid JSON request body.' });
  }
  response.status(500).json({ message: 'Internal server error.' });
});

export default app;
