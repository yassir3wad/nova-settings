<?php

namespace Yassir3wad\Settings;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Yassir3wad\Settings\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'settings');

        $this->publishes([
            __DIR__.'/../config/settings.php' => config_path('settings.php'),
        ]);
        
          $this->publishes([
            __DIR__.'/../resources/lang/' => resource_path('lang/vendor/nova-settings'),
        ]);
        
         $this->registerTranslations();

        $this->publishes([
            __DIR__ . '/../database/seeds/SettingsSeeder.php' => $this->app->databasePath() . "/seeds/SettingsSeeder.php",
        ], 'seeds');

        $this->app->booted(function () {
            $this->routes();
        });

        $this->loadMigrations();

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
            ->group(__DIR__ . '/../routes/api.php');

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
    
       protected function registerTranslations()
    {
        $this->loadJSONTranslationsFrom(__DIR__.'/../resources/lang');
        $this->loadJSONTranslationsFrom(resource_path('lang/vendor/nova-settings'));
    }
}
