<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app['config']->set('session.driver', 'cookie');
        $this->app['config']->set('cache.default', 'array');
        $this->app['config']->set('logging.default', 'stderr');
        $this->app['config']->set('view.compiled', base_path('.cache/views'));
    }
}
