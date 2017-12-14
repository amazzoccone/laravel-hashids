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
            __DIR__.'/config/hashids.php' => config_path('hashids.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind(Converter::class, function ($app) {
            $config = $app->config->get('hashids');

            return new Converter($config);
        });
    }
}