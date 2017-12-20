<?php

namespace Bondacom\LaravelHashids;

use Illuminate\Support\Collection;

class Checker
{
    /**
     * @var array
     */
    private $separators;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $blacklist;

    /**
     * @var \Illuminate\Support\Collection|boolean
     */
    private $whitelist;

    /**
     * Checker constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->separators = $config['separators'] ?? [];  //before key name
        $this->whitelist = is_array($config['whitelist']) ? $this->getCombinations($config['whitelist']) : $config['whitelist'];
        $this->blacklist = $this->getCombinations($config['blacklist']);
    }

    /**
     * @param string $field
     * @return bool
     */
    public function isInBlacklist($field)
    {
        return $this->isInList($this->blacklist, $field);
    }

    /**
     * @param string $field
     * @return boolean
     */
    public function isInWhitelist($field)
    {
        if (is_bool($this->whitelist)) {
            return $this->whitelist;
        }

        return $this->isInList($this->whitelist, $field);
    }

    /**
     * @param $field
     * @return bool
     */
    public function isAnId($field)
    {
        return $this->isInWhitelist($field) && !$this->isInBlacklist($field);
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
     * @return \Illuminate\Support\Collection
     */
    private function getCombinations(array $items)
    {
        $combinations = collect([]);
        foreach ($items as $item) {
            foreach ($this->separators as $separator) {
                $combinations->push($separator.$item);
            }
        }
        return $combinations;
    }

    /**
     * @param \Illuminate\Support\Collection $list
     * @param string $field
     * @return \Illuminate\Support\Collection
     */
    private function isInList(Collection $list, $field)
    {
        return $list->contains(function ($value) use ($field) {
            return $field == $value || ends_with($field, $value);
        });
    }
}