HealNest — Vercel deployment notes

Overview
--------
This project is a Laravel backend with a Vite-built frontend. Vercel is best used to host the static frontend assets. Laravel (PHP) should be hosted on a PHP-capable host (Render, Fly, DigitalOcean App Platform, Railway) and exposed as an API for the frontend.

Recommended approach
--------------------
- Deploy frontend assets to Vercel (static site). Build outputs are in `public/` (Vite writes to `public/build`).
- Deploy Laravel backend to a PHP host and set environment variables for production DB, MAIL, and APP_KEY.

Quick steps to prepare frontend for Vercel
-----------------------------------------
1. Locally build assets:
```bash
npm ci
npm run build
```
2. Confirm built files are present under `public/build`.
3. Create a Vercel project pointed at this repo and set the build command to `npm run build` and output directory to `public`.

Notes about server-side routes
-----------------------------
- Vercel serves static files. Laravel's `index.php` requires PHP execution; serving the full Laravel app from Vercel requires a custom PHP server or a different host.
- If you need a single-host solution, consider deploying via a Docker image to a provider that supports PHP.

Environment variables
---------------------
Keep sensitive environment variables (DB, MAIL, APP_KEY) only on the backend host. Frontend only needs public API base URL.

Further steps I can do
----------------------
- Create a GitHub Actions workflow to build frontend and push static artifacts to a branch for Vercel.
- Create a small `vercel.json` that sets `@vercel/static-build` with `public` as the `distDir`.

