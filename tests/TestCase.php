<?php

class TestCase extends Orchestra\Testbench\TestCase
{
    /**
     * @param $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            '\Bondacom\LaravelHashids\HashidsServiceProvider'
        ];
    }

}