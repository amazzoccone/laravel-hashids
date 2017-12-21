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
     * @var \Hashids\Hashids
     */
    private $hashids;

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

        $this->hashids = app(Hashids::class);
    }

    /**
     * @param array $attributes
     * @param array $config
     * @param Closure $closure
     * @return \Illuminate\Support\Collection
     */
    protected function mapOnlyValid(array $attributes, array $config, Closure $closure)
    {
        $checker = new Checker($config);

        return $this->mapValues($attributes, $checker, $closure);
    }

    /**
     * @param array $attributes
     * @param \Bondacom\LaravelHashids\Checker $checker
     * @param Closure $closure
     * @param bool $withoutValidation
     * @return mixed
     */
    private function mapValues(array $attributes, Checker $checker, Closure $closure, $withoutValidation = false)
    {
        $collection = collect($attributes);
        return $collection->map(function ($value, $key) use ($attributes, $checker, $closure, $withoutValidation) {
            try {
                if(empty($value) || $checker->isInBlacklist($key)) {  //skip if is in blacklist
                    return $value;
                }

                $valid = $withoutValidation ?: $checker->isAnId($key);
                if (is_array($value)) {
                    return $this->mapValues($value, $checker, $closure, $valid); //Ex.: users_id=[13,92,7] or orders=[..]
                }
                if (!$valid) {
                    return $value;
                }

                return $closure($value);

            } catch (Exception $e) {
                throw new ConverterException();
            }
        });
    }

    /**
     * Decode hash ids to system ids
     *
     * @param array $parameters
     * @param string $configName
     * @return \Illuminate\Support\Collection
     */
    protected function decode(array $parameters, string $configName)
    {
        $config = $this->config($configName);
        return $this->mapOnlyValid($parameters, $config, function ($value) {
            return $this->hashids->decode($value)[0];
        });
    }

    /**
     * Encode system ids to hash ids
     *
     * @param array $attributes
     * @param string|null $configName
     * @return \Illuminate\Support\Collection
     */
    protected function encode(array $attributes, $configName = null)
    {
        $config = $this->config($configName);
        return $this->mapOnlyValid($attributes, $config, function ($value) {
            return $this->hashids->encode($value);
        });
    }

    /**
     * @param string $field
     * @return array
     */
    private function config(string $field = null)
    {
        if (is_null($field)) {
            return $this->defaultConfig;
        }

        return array_merge($this->defaultConfig, $this->customsConfig[$field]);
    }
}