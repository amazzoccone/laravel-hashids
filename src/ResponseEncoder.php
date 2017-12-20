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
    public function handle(Response $response)
    {
        $this->response = clone $response;
        $this->hashids = app(Hashids::class);

        $content = json_decode($this->response->getContent(), true);
        //IMPROVE: Maybe could be especify structure of api content to be encoded. Ex.: "data"
        $encodedContent = json_encode($this->encode($content));
        $this->response->setContent($encodedContent);

        return $this->response;
    }

    /**
     * Encode system ids to hash ids
     *
     * @param array $attributes
     * @return array
     */
    protected function encode(array $attributes)
    {
        return $this->mapValues($attributes, $this->config(), function ($value) {
            return $this->hashids->encode($value);
        });
    }
}