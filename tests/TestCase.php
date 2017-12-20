<?php

namespace Bondacom\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Hashids\Hashids;

abstract class TestCase extends BaseTestCase
{
    /**
     * @var \Hashids\Hashids
     */
    private $hashids;

    public function setUp()
    {
        parent::setUp();

        $this->hashids = app(Hashids::class);
    }

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

    /**
     * @param $value
     * @return string
     */
    public function encode($value)
    {
        return $this->hashids->encode($value);
    }

    /**
     * @param $value
     * @return string
     */
    public function decode($value)
    {
        return $this->hashids->decode($value)[0];
    }
}
