<?php

namespace Okatsuralau\LaravelTranslator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Collection;
use Okatsuralau\LaravelTranslator\Http\Middleware\SetLocale;
use Okatsuralau\LaravelTranslator\Translator\Collection as TranslatorCollection;

class LaravelTranslatorServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->bootTranslatorCollectionMacros();

        // Register Route Middleware
        $this->app['router']->middleware('SetLocale', SetLocale::class);

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-translator.php', 'LaravelTranslator');

        // Register the service the package provides.
        $this->app->singleton('LaravelTranslator', function ($app) {
            return new LaravelTranslator;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['LaravelTranslator'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/laravel-translator.php' => config_path('laravel-translator.php'),
        ], 'laravel-translator.config');
    }

    protected function bootTranslatorCollectionMacros()
    {
        Collection::macro('translate', function () {
            $transtors = [];
            foreach ($this->all() as $item) {
                $transtors[] = call_user_func_array([$item, 'translate'], func_get_args());
            }
            return new TranslatorCollection($transtors);
        });
    }
}
