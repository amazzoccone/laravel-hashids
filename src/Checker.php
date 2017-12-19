<?php

namespace Bondacom\LaravelHashids;

class Checker
{
    /**
     * @var array
     */
    private $separators;

    /**
     * @var array
     */
    private $blacklist;

    /**
     * @var array
     */
    private $whitelist;

    /**
     * Checker constructor.
     * @param array $keys
     */
    public function __construct(array $keys)
    {
        $this->separators = ['_', '-', '']; //before key name
        $this->whitelist = $this->getCombinations($keys['whitelist'] ?? ['id']);
        $this->blacklist = $this->getCombinations($keys['blacklist'] ?? []);
    }

    /**
     * @param string $field
     * @return bool
     */
    public function isInBlacklist($field)
    {
        return in_array($field, $this->blacklist);
    }

    /**
     * @param string $field
     * @return boolean
     */
    public function isInWhitelist($field)
    {
        return in_array($field, $this->whitelist);
    }

    /**
     * @param $field
     * @return bool
     */
    public function isAnId($field)
    {
        return in_array($field, $this->whitelist);
    }

    /**
     * Dynamically retrieve property
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (! $key) {
            return;
        }

        if (property_exists(self::class, $key)) {
            return $this->{$key};
        }
    }

    /**
     * @param array $items
     * @return array
     */
    private function getCombinations(array $items)
    {
        $combinations = [];
        foreach ($items as $item) {
            foreach ($this->separators as $separator) {
                array_push($combinations, $separator.$item);
            }
        }
        return $combinations;
    }

}