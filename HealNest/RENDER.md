HealNest on Render

This repo is now prepared for Render as a Docker-based PHP web service.

What Render will do
-------------------
- Build the image from `Dockerfile`.
- Run Laravel with `php artisan serve` on `0.0.0.0`.
- Expose the app on the port Render assigns through `PORT`.

Before deploying
----------------
Set these environment variables in Render:
- `APP_NAME`
- `APP_ENV=production`
- `APP_KEY`
- `APP_DEBUG=false`
- `APP_URL`
- `DB_CONNECTION` and database credentials
- MongoDB connection settings if you keep using MongoDB
- Mail settings if email is used

Recommended Render setup
------------------------
1. Create a new Web Service in Render.
2. Connect this GitHub repository.
3. Use the generated `render.yaml` or choose the Dockerfile manually.
4. Add the required environment variables.
5. Deploy.

Notes
-----
- The current Dockerfile uses `php artisan serve`, which is simple and works for testing and coursework hosting.
- For heavier production usage, switch to nginx + php-fpm later.
