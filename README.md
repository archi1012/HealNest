# HealNest
HealNest is a Laravel MVC-based web application for monitoring and assessing children’s mental health through emotional tracking, digital assessments, and analytics dashboards to support early detection and intervention.

## Local Run

Run the app locally with these steps:

```bash
cd HealNest
composer install
cp .env.example .env
php artisan key:generate
npm install
composer run dev
```

The app will run on `http://127.0.0.1:8000` and the Vite assets will run through the local dev server started by the `dev` script.
