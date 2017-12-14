<?php

namespace Bondacom\LaravelHashids;

use Bondacom\LaravelHashids\Exceptions\PublicIdsConverterException;
use Closure;
use Exception;
use Hashids\Hashids;

class Converter
{
    /**
     * @var \Hashids\Hashids
     */
    private $hashids;

    /**
     * @var Checker
     */
    private $checker;

    /**
     * PublicIdsConverter constructor.
     */
    public function __construct($config)
    {
        $salt = $config['salt'];
        $minLength = $config['length'];
        $keyName = $config['keyName'];

        $this->hashids = new Hashids($salt, $minLength);
        $this->checker = new Checker($keyName);
    }

    /**
     * Decode hash ids to system ids
     *
     * @param array $attributes
     * @param bool $onlyIds
     * @return array
     */
    public function decode(array $attributes, $onlyIds = true)
    {
        return $this->mapValues($attributes, function ($value) {
            return $this->hashids->decode($value)[0];
        }, $onlyIds);
    }

    /**
     * Encode system ids to hash ids
     *
     * @param array $attributes
     * @return array
     */
    public function encode(array $attributes)
    {
        return $this->mapValues($attributes, function ($value) {
            return $this->hashids->encode($value);
        }, true);
    }

    /**
     * @param array $attributes
     * @param Closure $closure
     * @param bool $onlyIds
     * @return array
     */

    protected function mapValues(array $attributes, Closure $closure, $onlyIds)
    {
        $collection = collect($attributes);

        return $collection->map(function ($value, $key) use ($closure, $onlyIds, $attributes) {
            try {
                if (is_array($value)) {
                    return $this->mapValues($value, $closure, $onlyIds);
                }
                if ((!$onlyIds || $this->checker->isAnId($key)) && !is_null($value)) {
                    return $closure($value);
                }
                return $value;
            } catch (Exception $e) {
                throw new PublicIdsConverterException();
            }
        })->toArray();
    }
}