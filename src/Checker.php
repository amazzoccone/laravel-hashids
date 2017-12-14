<?php

namespace Bondacom\LaravelHashids;

class Checker
{
    /**
     * @var string
     */
    private $keyName;

    /**
     * Checker constructor.
     * @param string $keyName
     */
    public function __construct(string $keyName = null)
    {
        //TODO: Add posibility to have a blacklist
        $this->keyName = $keyName ?: 'id';
    }

    /**
     * @param $field
     * @return bool
     */
    public function isAnId($field)
    {
        if ($field === $this->keyName) {
            return true;
        }

        $acceptedEndingIds = ['_'.$this->keyName, '-'.$this->keyName];
        $length = strlen($this->keyName) + 1;

        return in_array(substr($field, -$length), $acceptedEndingIds);
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

}