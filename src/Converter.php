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
     * @param $mixed
     * @return mixed
     */
    abstract public function handle($mixed);

    /**
     * Converter constructor.
     * @param array $defaultConfig
     * @param array $customsConfig
     */
    public function __construct(array $defaultConfig, array $customsConfig = [])
    {
        $this->defaultConfig = $defaultConfig;
        $this->customsConfig = $customsConfig;
    }

    /**
     * @param array $attributes
     * @param array $config
     * @param Closure $closure
     * @param bool|false $withoutValidation
     * @return \Illuminate\Support\Collection
     */
    protected function mapValues(array $attributes, array $config, Closure $closure, $withoutValidation = false)
    {
        $this->checker = new Checker($config);

        $collection = collect($attributes);
        return $collection->map(function ($value, $key) use ($closure, $withoutValidation, $attributes, $config) {
            try {
                if (is_array($value)) {
                    return $this->mapValues($value, $config, $closure, is_int($key));   //is_int for not assoc arrays. Ex.: user_ids=[142,211,84]
                }

                $valid = $withoutValidation ?: $this->checker->isAnId($key);

                if (empty($value) || !$valid) {
                    return $value;
                }

                return $closure($value);

            } catch (Exception $e) {
                throw new ConverterException();
            }
        });
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