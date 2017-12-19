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
     * @var \Illuminate\Http\Response
     */
    private $response;

    /**
     * @param $response
     * @return mixed
     */
    public function handle($response)
    {
        $this->converter = app(Converter::class);
        $this->response = clone $response;

        $content = json_decode($this->response->getContent(), true);
        $encodedContent = json_encode($this->converter->encode($content));
        $this->response->setContent($encodedContent);

        return $this->response;
    }
}