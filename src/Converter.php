<?php

namespace Bondacom\LaravelHashids;

use Bondacom\LaravelHashids\Exceptions\ConverterException;
use Closure;
use Exception;
use Hashids\Hashids;

abstract class Converter
{
    /**
     * @var array
     */
    private $defaultConfig;
    /**
     * @var array
     */
    private $customsConfig;

    /**
     * @var \Bondacom\LaravelHashids\Checker
     */
    private $checker;

    /**
     * @return mixed
     */
    abstract protected function handle();

    /**
     * Converter constructor.
     * @param array $defaultConfig
     * @param array $customsConfig
     */
    public function __construct(array $defaultConfig, array $customsConfig)
    {
        $this->defaultConfig = $defaultConfig;
        $this->customsConfig = $customsConfig;
    }

    /**
     * @param array $attributes
     * @param array $config
     * @param Closure $closure
     * @param bool|false $withoutValidation
     * @return array
     */
    protected function mapValues(array $attributes, array $config, Closure $closure, $withoutValidation = false)
    {
        $this->checker = new Checker($config);

        $collection = collect($attributes);
        return $collection->map(function ($value, $key) use ($closure, $withoutValidation, $attributes) {
            try {
                $valid = $withoutValidation ?: $this->checker->isAnId($key);

                if (empty($value) || !$valid) {
                    return $value;
                }

                if (is_array($value)) {
                    return $this->mapValues($value, $closure, false);
                }

                return $closure($value);

            } catch (Exception $e) {
                throw new ConverterException();
            }
        })->toArray();
    }

    /**
     * @param string $field
     * @return array
     */
    protected function config(string $field = null)
    {
        if (is_null($field)) {
            return $this->defaultConfig;
        }

        return array_merge($this->defaultConfig, $this->customsConfig[$field]);
    }
}