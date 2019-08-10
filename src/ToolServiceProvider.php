<?php

namespace Yassir3wad\Settings;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Yassir3wad\Settings\Http\Middleware\Authorize;
use Yassir3wad\Settings\Resources\Setting;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'settings');
        $this->mergeConfigFrom(__DIR__ . '/../config/settings.php', 'settings');

        $this->app->booted(function () {
            $this->routes();
        });

        $this->loadMigrations();

        Nova::resources([
            Setting::class
        ]);
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/settings')
            ->group(__DIR__.'/../routes/api.php');

    }

    private function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
