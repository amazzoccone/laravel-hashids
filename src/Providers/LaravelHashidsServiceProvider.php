<?php

namespace Bondacom\LaravelHashids\Providers;

use Bondacom\LaravelHashids\Converter;
use Illuminate\Support\ServiceProvider;

class LaravelHashidsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/hashids.php' => config_path('hashids.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $config = config('hashids');
        $this->app->bind(Converter::class, function ($app) use ($config) {
            return new Converter($config);
        });
    }
}