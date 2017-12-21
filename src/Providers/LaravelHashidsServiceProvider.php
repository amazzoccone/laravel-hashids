<?php

namespace Bondacom\LaravelHashids\Providers;

use Bondacom\LaravelHashids\RequestDecoder;
use Bondacom\LaravelHashids\ResponseEncoder;
use Hashids\Hashids;
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
        $this->mergeConfigFrom(
            __DIR__.'/../config/hashids.php', 'hashids'
        );

        $config = config('hashids');

        $this->app->bind(RequestDecoder::class, function ($app) use ($config) {
            return new RequestDecoder($config['default'], $config['customizations']['request']);
        });
        $this->app->bind(ResponseEncoder::class, function ($app) use ($config) {
            return new ResponseEncoder($config['default'], $config['customizations']['response']);
        });

        $this->app->bind(Hashids::class, function ($app) use ($config) {
            $salt = $config['system']['salt'];
            $length = $config['system']['length'];
            $alphabet = $config['system']['alphabet'];

            return new Hashids($salt, $length, $alphabet);
        });
    }
}