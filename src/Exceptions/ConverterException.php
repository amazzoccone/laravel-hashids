<?php

namespace Bondacom\LaravelHashids\Exceptions;

use Exception;

class ConverterException extends Exception
{
    /**
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($code = 0, Exception $previous = null)
    {
        parent::__construct('Fails to convert public ids', $code, $previous);
    }
}