# HealNest

HealNest is a full-stack mental wellness application built with a modern JavaScript stack.

Architecture
- Frontend: Next.js + React.js + JavaScript + Tailwind CSS
- Backend: Node.js + Express.js
- Database: PostgreSQL (Neon)

Project structure
- `frontend/` — web app
- `backend/` — REST API and database access

Setup
1. Copy the environment examples:
   - `frontend/.env.example` → `frontend/.env.local`
   - `backend/.env.example` → `backend/.env`
2. Fill in the required values for your local environment.

Environment variables
Backend (`backend/.env`)
- `PORT=4000`
- `FRONTEND_ORIGIN=http://localhost:3000`
- `FRONTEND_URL=http://localhost:3000`
- `JWT_SECRET=your_secret_key`
- `DATABASE_URL=postgres://user:password@host:port/database`
- `DATABASE_SSL=false` or `true` depending on your PostgreSQL provider
- `SMTP_HOST`, `SMTP_PORT`, `SMTP_USER`, `SMTP_PASSWORD`, `SMTP_FROM` (optional)

Frontend (`frontend/.env.local`)
- `NEXT_PUBLIC_API_URL=http://localhost:4000/api/v1`

Run frontend
```bash
cd frontend
npm install
npm run dev
```
Open: http://localhost:3000

Run backend
```bash
cd backend
npm install
npm run dev
```
API base: http://localhost:4000/api/v1

Production build
```bash
cd frontend
npm run build
npm run start
```
