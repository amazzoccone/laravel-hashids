<?php

namespace Bondacom\LaravelHashids;

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
     * @param \Illuminate\Http\Response $response
     * @return \Illuminate\Http\Response
     */
    public function handle($response)
    {
        $this->response = $response;

        $this->decodeHeaders()
            ->decodeContent();

        return $this->response;
    }

    /**
     * @return $this
     */
    protected function decodeHeaders()
    {
        $headers = $this->response->headers;
        $this->response->headers = $this->encode($headers, 'headers');

        return $this;
    }

    /**
     * @return $this
     */
    protected function decodeContent()
    {
        $content = json_decode($this->response->getContent(), true);
        $this->response->setContent($this->encode($content, 'content'));

        return $this;
    }
}