<?php

namespace Bondacom\LaravelHashids;

/**
 * Class ResponseEncoder
 *
 * Convert response ids to public ids
 */
class ResponseEncoder
{
    /**
     * @var \Bondacom\LaravelHashids\Converter
     */
    private $converter;

    /**
     * @param $response
     * @return mixed
     */
    public function handle($response)
    {
        $this->converter = app(Converter::class);

        $content = json_decode($response->getContent(), true);
        $encodedContent = json_encode($this->converter->encode($content));
        $response->setContent($encodedContent);

        return $response;
    }
}