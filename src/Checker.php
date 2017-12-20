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
        $this->blacklist = collect($config['blacklist']);
        $this->whitelist = is_bool($config['whitelist']) ? $config['whitelist'] : collect($config['whitelist']);
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
     * @param \Illuminate\Support\Collection $list
     * @param string $field
     * @return \Illuminate\Support\Collection
     */
    private function isInList(Collection $list, $field)
    {
        if ($list->contains(function ($value) use ($field) {
            return $field === $value;
        })) {
            return true;
        }

        return $this->getCombinations($list)->contains(function ($value) use ($field) {
            return ends_with($field, $value);
        });
    }

    /**
     * @param \Illuminate\Support\Collection $items
     * @return \Illuminate\Support\Collection
     */
    private function getCombinations(Collection $items)
    {
        $combinations = collect([]);
        foreach ($items as $item) {
            foreach ($this->separators as $separator) {
                $combinations->push($separator.$item);
            }
        }
        return $combinations;
    }
}