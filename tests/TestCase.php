<?php

namespace Bondacom\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Bondacom\LaravelHashids\Providers\LaravelHashidsServiceProvider::class
        ];
    }
}
