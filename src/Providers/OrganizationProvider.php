<?php

namespace Sbash\Orgmgmt\Providers;

use Illuminate\Support\ServiceProvider;

class OrganizationProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->publishes([
            __DIR__.'/../../assets/public' => public_path('vendor/orgmgmt'),
        ], 'public');
        $this->loadViewsFrom(__DIR__.'/../views', 'orgmgmt');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'orgmgmt');

        $this->publishes([
	        __DIR__.'/../views' => resource_path('views'),
	    ]);
    }
}