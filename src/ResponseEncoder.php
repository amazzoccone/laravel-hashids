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

        $this->encodeHeaders()
            ->encodeContent();

        return $this->response;
    }

    /**
     * @return $this
     */
    protected function encodeHeaders()
    {
        $headers = $this->response->headers->all();
        $newHeaders = $this->encode($headers, 'headers')->toArray();
        $this->response->headers->replace($newHeaders);

        return $this;
    }

    /**
     * @return $this
     */
    protected function encodeContent()
    {
        $content = json_decode($this->response->getContent(), true);
        $newContent = $this->encode($content, 'content')->toArray();
        $this->response->setContent($newContent);

        return $this;
    }
}