<?php

namespace Bondacom\LaravelHashids;

use Hashids\Hashids;
use Illuminate\Http\Response;

/**
 * Class ResponseEncoder
 *
 * Convert response ids to public ids
 */
class ResponseEncoder extends Converter
{
    /**
     * @var \Illuminate\Http\Response
     */
    private $response;

    /**
     * @var \Hashids\Hashids
     */
    private $hashids;

    /**
     * @param \Illuminate\Http\Response $response
     * @return \Illuminate\Http\Response
     */
    public function handle($response)
    {
        $this->response = clone $response;
        $this->hashids = app(Hashids::class);

        $this->decodeHeaders()
            ->decodeContent();

        return $this->response;
    }

    /**
     * @return $this
     */
    private function decodeHeaders()
    {
        $headers = $this->response->headers;
        $this->response->headers = $this->encode($headers);

        return $this;
    }

    /**
     * @return $this
     */
    private function decodeContent()
    {
        $content = json_decode($this->response->getContent(), true);
        $this->response->setContent($this->encode($content));

        return $this;
    }

    /**
     * Encode system ids to hash ids
     *
     * @param array $attributes
     * @return \Illuminate\Support\Collection
     */
    protected function encode(array $attributes)
    {
        return $this->mapValues($attributes, $this->config(), function ($value) {
            return $this->hashids->encode($value);
        });
    }
}