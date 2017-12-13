# Laravel Hash ids

###### [FAQ](#faq) | [Contributing](https://github.com/bondacom/laravel-hashids/blob/master/CONTRIBUTING.md)

> Laravel Hashids is a library which provides a middleware to encode/decode ids from requests and responses.
> This will convert your database system ids to short unique ids unguessable, so the consumers of your API 
will never be concern about the real ids. On the other side, when a consumer request your API using the hash ids, this will decode it.

## Getting Started

### Installation

> *Note: Laravel Hashids requires at least PHP v7.1.*

To use Laravel Hashids in your project, run:
```
composer require Bondacom/laravel-hashids
```

> **Note**: For Laravel less than 5.5 remember to register manually the service provider!

### Configuration
Copy the config file into your project by running
```
php artisan vendor:publish --provider="Bondacom\LaravelHashids\Providers\HashidsServiceProvider" --tag="config"
```

### Usage

It's really simple! 
Register the middleware. 
```
// app/Http/Kernel.php

class Kernel extends HttpKernel
{
    protected $middleware = [
        // ...
        \App\Http\Middleware\MyRobotsMiddleware::class,
        \Bondacom\LaravelHashids\Middleware\PublicIds::class,
    ];
    
    ...
}
```

>IMPORTANT: Register it before Laravel bindings middleware.

## Contributing to Laravel Hashids

Check out [contributing guide](https://github.com/bondacom/laravel-hashids/blob/master/CONTRIBUTING.md) to get an overview of Laravel Hashids development.

# FAQ

#### Q: Which PHP version does Laravel Hashids use?

Look for [composer.json](https://github.com/bondacom/laravel-hashids/blob/master/composer.json).